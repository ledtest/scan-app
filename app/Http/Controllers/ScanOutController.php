<?php

namespace App\Http\Controllers;

use App\Exports\SamsungOutExport;
use App\Models\SamsungOut;
use App\Models\ScanProductType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Mike42\Escpos\Printer;
use Mpdf\Mpdf;
use Rawilk\Printing\Facades\Printing;
use thiagoalessio\TesseractOCR\TesseractOCR;

use Mike42\Escpos\PrintConnectors\FilePrintConnector;

class ScanOutController extends Controller
{
    //

    public function add()
    {
        return Inertia::render('Samsung/add', ['outs' => '']);
    }

    public function renderSamsungOutIndex()
    {
        return Inertia::render('Samsung/Index', ['outs' => '']);
    }

    /**
     * 切换下一箱条码，箱号+1
     * @param Request $request
     */
    public function scanNextBox(Request $request)
    {

    }

    /**
     * 扫描入库逻辑：LOT + QTY + DC
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkSamsungOut(Request $request)
    {
        $postParams = $request->only(['box', 'group', 'scanCode', 'saveMessage', 'csrf']);

        $outs = SamsungOut::all()->toArray();
        if (empty($outs)) {
            $outs['box'] = 1;
            $outs['savedBox'] = $postParams['box'];
            $outs['group'] = intval($postParams['group']) + 1;
            $outs['savedGroup'] = $postParams['group'];
            $outs['savedScanCode'] = $postParams['scanCode'];
            $outs['saveMessage'] = 0;
            $outs['savedSaveMessage'] = 1;
        }

        $userID = Auth::id();
        $cachedScan = array_merge(['user_id' => $userID], $postParams);

        $cachedPrefix = 'scan';

        $cachedKey = 'samsung@user_id:' . $userID
            . ':box:' . $cachedScan['box'] . ':group:' . $cachedScan['group'];
        Redis::hset($cachedPrefix, $cachedKey,
            json_encode($cachedScan)
        );

        if (Redis::hexists($cachedPrefix, $cachedKey)) {
            $cached = Redis::hget($cachedPrefix, $cachedKey);
        }

        return Inertia::render('Samsung', [
            'outs' => $outs
        ]);

    }

    public function samsungDeleteGroup(Request $request)
    {
        $outs['box'] = 1;
        $outs['group'] = 1;
        $outs['scanCode'] = '';
        $outs['saveMessage'] = 0;
        $outs['savedSaveMessage'] = 0;

        if ($request->has('id')) {
            SamsungOut::find($request->get('id'))->update(['is_confirmed' => 0]);
            $outs['scanedGroup'] = SamsungOut::where(
                [
                    'user_id' => Auth::id(),
                    /*'product_type_id' => $request->get('product_type_id'),*/
                    'is_confirmed' => 1
                ]
            )->get();

            $outs['scanedGroup'] = SamsungOut::where([
                'user_id' => Auth::id(),
                /*'product_type_id' => $request->get('product_type_id'),*/
                'is_confirmed' => 1
            ])->get();

            /*            return Inertia::render('SamsungIndex', [
                            'outs' => $outs
                        ]);*/

            $outs['product_type_id'] = $request->get('product_type_id');

            //return $this->testAction($outs);
            return $this->deleteAction($request->all());
        }
    }

    public function deleteAction($row)
    {
        // 删除扫描行后的页面加载
        $max = DB::table('samsungOuts')->where(
            [
                'id' => DB::raw("(select max(`id`) from samsungOuts)"),
                'user_id' => Auth::id(),
                'product_type_id' => $row['product_type_id'],
                'is_confirmed' => 1
            ]
        )->first();

        $outs['box'] = $row['box'];
        $outs['group'] = intval($row['group']) + 1;

        $scanedGroup = DB::table("samsungOuts")
            ->select()
            ->where([
                'user_id' => Auth::id(),
                'is_confirmed' => 1
            ])
            ->get();

        $outs['scanedGroup'] = $scanedGroup;

        $outs['scanType'] = 2;
        $outs['productType'] = ScanProductType::where(['id' => $row['product_type_id']])->first()->code;

        return Inertia::render('SamsungIndex', ['outs' => $outs]);
    }

    /*
     * 打印
     */
    public function samsungPrint(Request $request)
    {
        $productType = $request->get('productType');
        $selected = $request->get('selected');

        $productOriginPlace = $request->get('productOriginPlace');
        $productPO = $request->get('productPO');

        $template = SamsungOut::whereIn('id', $selected)->get();

        $mpdf = new Mpdf([
            'format' => [100, 70],
            'default_font_size' => 16,
            "autoScriptToLang" => true,
            "autoLangToFont" => true,
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 10,
        ]);

        $mpdf->SetDisplayMode('fullpage');

        foreach ($template as $key => $value) {
            $currentProductType = ScanProductType::where(['id' => $value['product_type_id']])->first();
            $htmlTable = <<<EOD
<style>
body {
    font-family: serif;
    font-size: 16pt;
}
.tbl {width:800px;border-collapse: collapse;}
</style>
<table align="center" cellspacing="0" cellpadding="0" border="0" class="tbl">

<tbody>
    <tr>
        <td style="border: 2px solid #666;padding-left:30px;line-height:50px;font-size:30px;">原廠型號</td>
        <td style="border: 2px solid #666;padding-left:60px;padding-right:60px;line-height:50px;font-size:30px;">{$currentProductType->code}</td>
    </tr>
    <tr>
        <td style="border: 2px solid #666;padding-left:30px;line-height:50px;font-size:30px;">品牌</td>
        <td style="border: 2px solid #666;padding-left:60px;padding-right:60px;line-height:50px;font-size:30px;">SAMSUNG</td>
    </tr>
	<tr>
        <td style="border: 2px solid #666;padding-left:30px;line-height:50px;font-size:30px;">數量</td>
        <td style="border: 2px solid #666;padding-left:60px;padding-right:60px;line-height:50px;font-size:30px;">{$value->qty}PCS</td>
    </tr>
	<tr>
        <td style="border: 2px solid #666;padding-left:30px;padding-right:5px;line-height:50px;font-size:30px;">DATE CODE</td>
        <td style="border: 2px solid #666;padding-left:60px;padding-right:60px;line-height:50px;font-size:30px;">{$value->dc}+</td>
    </tr>
    <tr>
    <td style="border: 2px solid #666;padding-left:30px;line-height:50px;font-size:30px;">LOT NO</td>
    <td style="border: 2px solid #666;padding-left:60px;padding-right:60px;line-height:50px;font-size:30px;">{$value->lot}</td>
</tr>
</tbody>
<tr>
<td style="border: 2px solid #666;padding-left:30px;line-height:50px;font-size:30px;">產地</td>
<td style="border: 2px solid #666;padding-left:60px;padding-right:60px;line-height:50px;font-size:30px;">{$productOriginPlace}</td>
</tr>
<tr>
<td style="border: 2px solid #666;padding-left:30px;line-height:50px;font-size:30px;">PO 號碼</td>
<td style="border: 2px solid #666;padding-left:60px;padding-right:60px;line-height:50px;font-size:30px;">{$productPO}</td>
</tr>
</table>
EOD;
            $mpdf->WriteHTML($htmlTable);
        }

        $filename = storage_path() . '/samsung_label.pdf';
        $mpdf->Output($filename);
        return Inertia::render('SamsungPrint');

    }

    /**
     * 补打
     * @param Request $request
     */
    public function samsungForcePrint(Request $request)
    {
        $productType = $request->get('productType');
        $selected = $request->get('selected');
        $row = $request->get('row');

        $productOriginPlace = $request->get('productOriginPlace');
        $productPO = $request->get('productPO');

        $mpdf = new Mpdf([
            'format' => [100, 70],
            'default_font_size' => 16,
            "autoScriptToLang" => true,
            "autoLangToFont" => true,
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 10,
        ]);

        $mpdf->SetDisplayMode('fullpage');

        $htmlTable = <<<EOD
<style>
body {
    font-family: serif;
    font-size: 16pt;
}
.tbl {width:800px;border-collapse: collapse;}
</style>
<table align="center" cellspacing="0" cellpadding="0" border="0" class="tbl">

<tbody>
    <tr>
        <td style="border: 2px solid #666;padding-left:30px;line-height:50px;font-size:30px;">原廠型號</td>
        <td style="border: 2px solid #666;padding-left:60px;padding-right:60px;line-height:50px;font-size:30px;">{$productType}</td>
    </tr>
    <tr>
        <td style="border: 2px solid #666;padding-left:30px;line-height:50px;font-size:30px;">品牌</td>
        <td style="border: 2px solid #666;padding-left:60px;padding-right:60px;line-height:50px;font-size:30px;">SAMSUNG</td>
    </tr>
	<tr>
        <td style="border: 2px solid #666;padding-left:30px;line-height:50px;font-size:30px;">數量</td>
        <td style="border: 2px solid #666;padding-left:60px;padding-right:60px;line-height:50px;font-size:30px;">{$row['qty']}PCS</td>
    </tr>
	<tr>
        <td style="border: 2px solid #666;padding-left:30px;padding-right:5px;line-height:50px;font-size:30px;">DATE CODE</td>
        <td style="border: 2px solid #666;padding-left:60px;padding-right:60px;line-height:50px;font-size:30px;">{$row['dc']}+</td>
    </tr>
    <tr>
    <td style="border: 2px solid #666;padding-left:30px;line-height:50px;font-size:30px;">LOT NO</td>
    <td style="border: 2px solid #666;padding-left:60px;padding-right:60px;line-height:50px;font-size:30px;">{$row['lot']}</td>
</tr>
</tbody>
<tr>
<td style="border: 2px solid #666;padding-left:30px;line-height:50px;font-size:30px;">產地</td>
<td style="border: 2px solid #666;padding-left:60px;padding-right:60px;line-height:50px;font-size:30px;">{$productOriginPlace}</td>
</tr>
<tr>
<td style="border: 2px solid #666;padding-left:30px;line-height:50px;font-size:30px;">PO 號碼</td>
<td style="border: 2px solid #666;padding-left:60px;padding-right:60px;line-height:50px;font-size:30px;">{$productPO}</td>
</tr>
</table>
EOD;
        $mpdf->WriteHTML($htmlTable);

        $filename = storage_path() . '/samsung_label_force.pdf';
        $mpdf->Output($filename);
        return Inertia::render('SamsungForcePrint');
    }

    public function samsungExportExcel(Request $request) {
        $productType = $request->get('productType');
        $selected = $request->get('selected');

        $storeFile = Excel::store(new SamsungOutExport($selected), 'samsung'.'.xlsx');
        $outs['export_url'] = '';
        if ($storeFile) {
            $outs['export_url'] = storage_path('app/samsung.xlsx');
            $outs['export_productType'] = $productType;
            /*$outs['export_fileSize'] = Storage::size(storage_path('app/samsung.xlsx'));*/
        }
        return Inertia::render('SamsungExport', [
            'outs' => $outs
        ]);
    }

    public function deleteOrder(Request $request) {
        $productType = $request->get('productType');
        $selected = $request->get('selected');

        $outs = [
            'unlockScanProductType' => 'UNLOCK',
            'isBoxNotValid' => false,
            'isGroupNotValid' => false,
            'isScaned' => false,
            'isSaved' => false,
            'groupSavedStatus' => false,
        ];

        $productRecord = ScanProductType::where(['code' => $productType,'is_confirmed' => 1])->first();
        if (!empty($productRecord)) {
            DB::transaction(function () use($productType, $productRecord) {
                ScanProductType::where(['code' => $productType])->limit(1)->update(['is_confirmed' => -1]);
                SamsungOut::where([
                    'product_type_id' => $productRecord->id,
                    'is_confirmed' => 1
                ])->update(['is_confirmed' => -1]);
            });
        }

        $outs['scanType'] = 1;
        $outs['box'] = 1;
        $outs['group'] = 1;
        $outs['productType'] = '';

        Redis::hdel('samsung','lockedProductType');
        Redis::hdel('samsung','box' );
        Redis::hdel('samsung','group');
        Redis::hdel('samsung','token');

        //return \redirect()->route('samsungScanForm');
        return Inertia::render('SamsungIndex', ['outs' => $outs]);
    }

    /**
     * 加载扫描页
     * @return \Inertia\Response
     */
    public function renderSamsungOutForm(Request $request)
    {
        // 页面刷新，保持状态
        if (Redis::hexists('samsung', 'lockedProductType')) {
            $outs['scanType'] = 2;
            $outs['unlockScanProductType'] = 'LOCK';
            $outs['productType'] = Redis::hget('samsung','lockedProductType');
            $outs['box'] = Redis::hget('samsung','box');
            $outs['group'] = intval(Redis::hget('samsung','group')) + 1;

            $scanedGroup = DB::table("samsungOuts")
                ->select()
                ->where([
                    'user_id' => Auth::id(),
                    'token' => Redis::hget('samsung','token'),
                    'is_confirmed' => 1
                ])
                ->get();
            $outs['scanedGroup'] = $scanedGroup;

            $outs['isSaved'] = true;

            return Inertia::render('SamsungIndex', [
                'outs' => $outs
            ]);
        }

        if (count($request->all()) === 0) {
            // 页面首次加载
            $outs['scanType'] = 1;

            $outs['box'] = 1;
            $outs['group'] = 1;
            $outs['scanCode'] = '';
            $outs['saveMessage'] = 0;
            $outs['savedSaveMessage'] = 0;

            return Inertia::render('SamsungIndex', [
                'outs' => $outs
            ]);
        }

        // 删除扫描行后的页面加载
        $max = DB::table('samsungOuts')->where(
            [
                'id' => DB::raw("(select max(`id`) from samsungOuts)"),
                'user_id' => Auth::id(),
                'product_type_id' => $request->get('product_type_id'),
                'is_confirmed' => 1
            ]
        )->first();

        $outs['box'] = $max->box;
        $outs['group'] = $max->group;

        $outs['scanedGroup'] = SamsungOut::where(
            [
                'user_id' => Auth::id(),
                'product_type_id' => $request->get('product_type_id'),
                'is_confirmed' => 1
            ]
        )->get();

        return Inertia::render('SamsungIndex', [
            'outs' => $outs
        ]);

    }

    /**
     * 处理扫描型号，初始化盒扫描
     * @param Request $request
     */
    public function renderSamsungScan(Request $request)
    {
        $requestType = $request->get('requestType');
        switch ($requestType) {
            case 'deleteOrder':
                return $this->deleteOrder($request);
            default:
                break;
        }
        $isScaned = ScanProductType::where([
            'code' => $request->get('productType'),
            'status' => 1
        ]);

        $outs = [
            'unlockScanProductType' => 'UNLOCK',
            'isBoxNotValid' => false,
            'isGroupNotValid' => false,
            'isScaned' => false,
            'isSaved' => false,
            'groupSavedStatus' => false,
        ];

        Redis::hset('samsung', 'lockedProductType', $request->get('productType')?$request->get('productType')
            :$request->get('_currentScanProductType'));
        Redis::hset('samsung','box',$request->get('box'));
        Redis::hset('samsung','group',$request->get('group'));
        Redis::hset('samsung','token', $request->get('_token'));

        // 解锁 - 可以再次扫描新型号，并保持当前页面状态、已扫描行内容也不消失
        if (!empty($request->get('_lock')) &&
            strtoupper($request->get('_lock')) === 'UNLOCK') {

            $outs['scanType'] = 3; // 解锁

            $outs['box'] = !empty($request->get('box')) ? $request->get('box') : 1;
            $outs['group'] = !empty($request->get('group')) ? $request->get('group') : 1;

            $currentProductType = $request->get('_currentScanProductType');

            $scanedTypes = ScanProductType::where(
                [
                    'user_id' => Auth::id(),
                    'code' => $currentProductType,
                    'box' => $request->get('box'),
                    'active' => 1,
                    'status' => 1,
                    'token' => $request->get('_token')
                ]
            )->first();

            $outs['unlockScanProductType'] = '';
            //$outs['productType'] = $request->get('_currentScanProductType');
            $outs['productType'] = '';

            $scanedGroup = DB::table("samsungOuts")
                ->select()
                ->where([
                    'user_id' => Auth::id(),
                    'product_type_id' => $scanedTypes->id,
                    'is_confirmed' => 1
                ])
                ->get();

            $outs['scanedGroup'] = $scanedGroup;
            $outs['groupScanSavedStatus'] = 1;

            return Inertia::render('SamsungIndex', [
                'outs' => $outs
            ]);
        }

        // 一定要先检查box和group，如果传过来的box和group是无效的，直接返回错误提示，不要进行默认值的填充处理
        if ((empty($request->get('box')) ||
                intval($request->get('box')) < 0) ||
            (empty($request->get('group')) ||
                intval($request->get('group')) < 0
            )
        ) {
            $outs['isBoxNotValid'] = true;
            $outs['isGroupNotValid'] = true;

            return Inertia::render('SamsungIndex', [
                'outs' => $outs
            ]);
        }

        // 无效的型号条码
        if (empty($request->get('productType')) && empty($request->get('_currentScanProductType'))) {
            $outs['scanType'] = ''; // 无效的型号条码

            $outs['box'] = 1;
            $outs['group'] = 1;

            return Inertia::render('SamsungIndex', [
                'outs' => $outs
            ]);
        }

        // 根据_currentScanProductType可以知道是在进行型号扫描，还是在进行盒扫描
        if (!empty($request->get('_currentScanProductType')) && empty($request->get('productType'))) {
            // 盒扫描

            $outs['scanType'] = 2; // 盒扫描

            $productType = ScanProductType::where([
                'user_id' => Auth::id(),
                'code' => $request->get('_currentScanProductType')
            ])->first();

            DB::transaction(function () use ($request, $productType) {
                SamsungOut::create([
                    'user_id' => Auth::id(),
                    'product_type_id' => $productType->id,
                    'pn' => $request->get('_currentScanProductType'),
                    'code' => empty($request->get('scanCode')) ? '' : $request->get('scanCode'),
                    'box' => $request->get('box'),
                    'group' => $request->get('group'),
                    'lot' => substr($request->get('scanCode'), 0, 10),
                    'qty' => substr($request->get('scanCode'), 12, 3),
                    'dc' => substr($request->get('scanCode'), 15, 4),
                    'scan_order' => $request->get('group'), // 扫描序号理论上等于group组号，但是在删除后再次扫描就不一定相等了，因此单独记录起来
                    'printed_nums' => 0,
                    'reprinted_nums' => 0,
                    'is_confirmed' => 1,
                    'token' => $request->get('_token')
                ]);
            });

            $outs['groupScanCode'] = $request->get('scanCode');
            $outs['groupScanSavedStatus'] = 1;
            $outs['productType'] = $request->get('_currentScanProductType');
            $outs['unlockScanProductType'] = 'LOCK';
            $outs['box'] = $request->get('box');
            $outs['group'] = intval($request->get('group')) + 1;

            $outs['isSaved'] = true;

            /*            $scanedGroup = SamsungOut::where(
                            [
                                'user_id' => Auth::id(),
                                'product_type_id' => $productType->id,
                                'is_confirmed' => 1
                            ]
                        )->get();*/

            $scanedGroup = DB::table("samsungOuts")
                ->select()
                ->where([
                    'user_id' => Auth::id(),
                    /*'product_type_id' => $productType->id,*/
                    'is_confirmed' => 1
                ])
                ->get();

            $outs['scanedGroup'] = $scanedGroup;

            return Inertia::render('SamsungIndex', [
                'outs' => $outs
            ]);
        }

/*        if (Redis::hexists('samsung', 'product@' . $request->get('productType')
        )) {
            // 型号已扫码
            $outs['scanType'] = 2;

        } else {
            Redis::hset('samsung', 'product@' . $request->get('productType'), $request->get('productType'));
        }*/
        if (Redis::hexists('samsung', 'lockedProductType')) {
            // 型号已扫码
            $outs['scanType'] = 2;

        } else {
            Redis::hset('samsung', 'lockedProductType', $request->get('productType'));
        }

        // 已经扫描过的型号条码
        if ($isScaned->count() > 0) {
            // 加载出库明细
            $scanOuts = SamsungOut::where(
                [
                    'user_id' => Auth::id(),
                    'box' => $request->get('box'),
                    'group' => $request->get('group')
                ]
            )->get();

            $outs['box'] = $isScaned->first()->box;
            $outs['group'] = $isScaned->first()->group;
            $outs['productType'] = $request->get('productType');
            $outs['unlockScanProductType'] = 'LOCK';
            $outs['isScaned'] = true;

            return Inertia::render('SamsungIndex', [
                'outs' => $outs
            ]);
        }

        $outs['scanType'] = 2;

        DB::transaction(function () use ($request) {
            ScanProductType::create([
                'user_id' => Auth::id(),
                'code' => $request->get('productType'),
                'box' => $request->get('box'),
                'group' => $request->get('group'),
                'status' => 1,
                'token' => $request->get('_token')
            ]);
        });
        $outs['scannedAt'] = Carbon::now()->format('Y-m-d H:i:s');
        $outs['box'] = $request->get('box');
        $outs['group'] = $request->get('group');
        $outs['productType'] = $request->get('productType');
        $outs['unlockScanProductType'] = 'LOCK';
        $outs['isSaved'] = true;

        return Inertia::render('SamsungIndex', [
            'outs' => $outs
        ]);
    }
}
