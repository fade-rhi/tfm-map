<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Commune;
use JavaScript;

class CommuneController extends Controller {

    protected $commune;
    protected $inseecommuneopngo = [94068, 92040, 93064, 92063, 57463, 91122, 92077, 94076, 67482, 92064, 27681, 06027, 66136,
        94052, 14220, 95210, 57631, 14488, 92048, 37233, 92012, 34145, 60057, 06012, 93062, 92049,
        45208, 77014, 39478, 64122, 67447, 67043, 74191, 78358, 78551, 93010, 14754, 77288];

    public function __construct(Commune $commune) {
        $this->commune = $commune;
    }
    
   

    public function index() {
        $i = 0;
        foreach ($this->commune->where('COMM_BIZ', 1)->cursor() as $commune_biz) {
            $polygonCommBiz[$i] = $commune_biz->Geometry;
            $nomCommBiz[$i] = $commune_biz->NOM_COMM;
            $i++;
        }

        $j = 0;
        foreach ($this->commune->where('COMM_BIZ_ADJ_100', 1)->cursor() as $commune_biz_adj) {
            $polygonCommBizAdj[$j] = $commune_biz_adj->Geometry;
            $nomCommBizAdj[$j] = $commune_biz_adj->NOM_COMM;
            $j++;
        }


        JavaScript::put(['polygonBiz' => $polygonCommBiz, 'nomCommBiz' => $nomCommBiz]);
        JavaScript::put(['polygonBizAdj' => $polygonCommBizAdj, 'nomCommBizAdj' => $nomCommBizAdj]);
        return view('map');
    }


    public function updatecommbizlistindb() {
        $nbcommunes = count($this->inseecommuneopngo);

        for ($i = 0; $i < $nbcommunes; $i++) {
            $commune_biz = $this->commune->where('INSEE_COM', $this->inseecommuneopngo[$i])->first();
            $commune_biz->COMM_BIZ = 1;
            $commune_biz->save();
        }
    }

    public function updatecommbizadjlistindb() {
$distinhm100 = 100;
$distinhm50=50;
        foreach ($this->commune->where('COMM_BIZ', 1)->cursor() as $commune_biz) {
            $X_commune_biz = $commune_biz->X_CENTROID;
            $Y_commune_biz = $commune_biz->Y_CENTROID;

            foreach ($commune_biz_adj = $this->commune->where('X_CENTROID', '>', $X_commune_biz - $distinhm100)
                    ->where('X_CENTROID', '<', $X_commune_biz + $distinhm100)
                    ->where('Y_CENTROID', '>', $Y_commune_biz - $distinhm100)
                    ->where('Y_CENTROID', '<', $Y_commune_biz + $distinhm100)->cursor() as $commune_biz_adj) {
                $commune_biz_adj->COMM_BIZ_ADJ_100 = 1;
                $commune_biz_adj->save();
            }
        }
                foreach ($this->commune->where('COMM_BIZ', 1)->cursor() as $commune_biz) {
            $X_commune_biz = $commune_biz->X_CENTROID;
            $Y_commune_biz = $commune_biz->Y_CENTROID;

            foreach ($commune_biz_adj = $this->commune->where('X_CENTROID', '>', $X_commune_biz - $distinhm50)
                    ->where('X_CENTROID', '<', $X_commune_biz + $distinhm50)
                    ->where('Y_CENTROID', '>', $Y_commune_biz - $distinhm50)
                    ->where('Y_CENTROID', '<', $Y_commune_biz + $distinhm50)->cursor() as $commune_biz_adj) {
                $commune_biz_adj->COMM_BIZ_ADJ_50 = 1;
                $commune_biz_adj->save();
            }
        }
    }
    
    public function seedcomm(){
        $this->updatecommbizlistindb();
        $this->updatecommbizadjlistindb();
    }

}
