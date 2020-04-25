<?php
namespace App\Http\Controllers\Admin\Board;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Lib\Util;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;

class CdnController extends BaseController
{
    // CDN에 이미지 등록
    public function index(Request $request)
    {
//        $util = new Util();
//        $path = env('CDN_URL')."/pinxy/images/instagram/thumbnail/pinxy_instagram_1550564857_0.jpg";
//        $path = "http://cdn.ce1eb.com/pinxy/images/news/thumbnail/pinxy_news_1550036423_20.jpg";
//        $text_count = $util->detect_text_gcs($path);
//        return $text_count;
//        $ori_tags= DB::table('boards')
//            ->select('ori_tag')
//            ->where('type','instagram')
//            ->forPage(6,30)
//            ->get();
//        foreach($ori_tags as $ori_tag){
//            $taglist[]=json_decode($ori_tag->ori_tag);
//        }
//        foreach($taglist as $val){
//            foreach($val  as $del){
//                DB::insert('insert ignore into tags (name,board,type) values (?,?,?)',[$del,'instagram','ori']);
//            }
//        }
        return view('test');
    }

    public function store(Request $request)
    {
        $upload_url = '';

        // file save
        $util = new Util();
        $path = 'pinxy/images/test/';
        if ($request->hasFile('upload_file'))
        {
            $filename = $util->SaveThumbnailAzure($request->file('upload_file'), $path);
            $upload_url = "/".$path.$filename;
        }

        return redirect("/cdn?upload_url={$upload_url}");
    }
    public function detect_text_gcs($path)
    {
        $imageAnnotator = new ImageAnnotatorClient();

        # annotate the image
        $response = $imageAnnotator->textDetection($path);
        $texts = $response->getTextAnnotations();

        printf('%d texts found:' . PHP_EOL, count($texts));
        foreach ($texts as $text) {
            print($text->getDescription() . PHP_EOL);

            # get bounds
            $vertices = $text->getBoundingPoly()->getVertices();
            $bounds = [];
            foreach ($vertices as $vertex) {
                $bounds[] = sprintf('(%d,%d)', $vertex->getX(), $vertex->getY());
            }
            print('Bounds: ' . join(', ',$bounds) . PHP_EOL);
        }

        $imageAnnotator->close();
    }

}