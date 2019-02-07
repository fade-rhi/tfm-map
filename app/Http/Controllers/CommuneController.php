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
        $this->middleware('auth');
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
        
        $j = 0;
        foreach ($this->commune->where('COMM_OPPORT', 1)->cursor() as $commune_opport) {
            $polygonCommOpport[$j] = $commune_opport->Geometry;
            $nomCommOpport[$j] = $commune_opport->NOM_COMM;
            $j++;
        }

        if(isset($polygonCommBiz)){
        JavaScript::put(['polygonBiz' => $polygonCommBiz, 'nomCommBiz' => $nomCommBiz]);
        }
        if(isset($polygonCommBizAdj)){
        JavaScript::put(['polygonBizAdj' => $polygonCommBizAdj, 'nomCommBizAdj' => $nomCommBizAdj]);
        }
        if(isset($polygonCommOpport)){
        JavaScript::put(['polygonOpport' => $polygonCommOpport, 'nomCommOpport' => $nomCommOpport]);
        }
        return view('map');
    }

    public function updateCommBizListInDb() {
        $nbcommunes = count($this->inseecommuneopngo);

        for ($i = 0; $i < $nbcommunes; $i++) {
            $commune_biz = $this->commune->where('INSEE_COM', $this->inseecommuneopngo[$i])->first();
            $commune_biz->COMM_BIZ = 1;
            $commune_biz->save();
        }
    }

    public function razCommAdj() {
        foreach ($this->commune->where('COMM_BIZ_ADJ_100', 1)->cursor() as $commune_biz_adj) {
            $commune_biz_adj->COMM_BIZ_ADJ_100 = 0;
            $commune_biz_adj->save();
        }
    }

    public function updateCommBizAdjListInDb() {
        $this->razCommAdj();
        $distinhm100 = 80;
        foreach ($this->commune->where('COMM_BIZ', 1)->cursor() as $commune_biz) {
            $X_commune_biz = $commune_biz->X_CENTROID;
            $Y_commune_biz = $commune_biz->Y_CENTROID;

            foreach ($commune_biz_adj = $this->commune->where('X_CENTROID', '>', $X_commune_biz - $distinhm100)
                    ->where('X_CENTROID', '<', $X_commune_biz + $distinhm100)
                    ->where('Y_CENTROID', '>', $Y_commune_biz - $distinhm100)
                    ->where('Y_CENTROID', '<', $Y_commune_biz + $distinhm100)->cursor() as $comm_biz_adj) {
                $X_commune_biz_adj = $comm_biz_adj->X_CENTROID;
                $Y_commune_biz_adj = $comm_biz_adj->Y_CENTROID;

                if ($X_commune_biz_adj < sqrt(pow($distinhm100, 2) - pow($Y_commune_biz_adj - $Y_commune_biz, 2)) + $X_commune_biz) {
                    $comm_biz_adj->COMM_BIZ_ADJ_100 = 1;
                    $comm_biz_adj->save();
                }
            }
        }
        
        return redirect('/home');
    }


    public function admin() {
        $j = 0;
        foreach ($this->commune->where('COMM_BIZ', 1)->orderBy('INSEE_COM', 'asc')->cursor() as $commune_biz) {
            $codesInseeBiz[$j] = $commune_biz->INSEE_COM;
            $nomCommBiz[$j] = $commune_biz->NOM_COMM;
            $j++;
        }
        if($j==0){$codesInseeBiz=NULL; $nomCommBiz=NULL;}
        
        $j = 0;
        foreach ($this->commune->where('COMM_OPPORT', 1)->orderBy('INSEE_COM', 'asc')->cursor() as $commune_opport) {
            $codesInseeOpport[$j] = $commune_opport->INSEE_COM;
            $nomCommOpport[$j] = $commune_opport->NOM_COMM;
            $j++;
        }
        if($j==0){$codesInseeOpport=NULL; $nomCommOpport=NULL;}
        
            $j = 0;
        foreach ($this->commune->where('COMM_BIZ_ADJ_100', 1)->orderBy('INSEE_COM', 'asc')->cursor() as $commune_adj) {
            $codesInseeAdj[$j] = $commune_adj->INSEE_COM;
            $nomCommAdj[$j] = $commune_adj->NOM_COMM;
            $j++;
        }
        if($j==0){$codesInseeAdj=NULL; $nomCommAdj=NULL;}
        
        return view('backoffice')->with('codesInseeBiz', $codesInseeBiz)
                                 ->with('nomCommBiz', $nomCommBiz)
                                 ->with('codesInseeOpport', $codesInseeOpport)
                                 ->with('nomCommOpport', $nomCommOpport)
                                 ->with('codesInseeAdj', $codesInseeAdj)
                                 ->with('nomCommAdj', $nomCommAdj);
    }
    
    public function deleteBiz($insee){
            $commune_biz = $this->commune->where('INSEE_COM', $insee)->first();
            $commune_biz->COMM_BIZ = 0;
            $commune_biz->save();
            return redirect('/backoffice');
    }
    
    public function deleteOpport($insee){
            $commune_opport = $this->commune->where('INSEE_COM', $insee)->first();
            $commune_opport->COMM_OPPORT = 0;
            $commune_opport->save();
            return redirect('/backoffice');
    }
    
    public function addBiz(Request $request){
            $insee=$request->insee;
            $commune_biz = $this->commune->where('INSEE_COM', $insee)->firstorfail();
            $commune_biz->COMM_BIZ = 1;
            $commune_biz->save();
            return redirect('/backoffice');
    }
    
    public function addOpport(Request $request){
        $insee=$request->insee;    
        $commune_opport = $this->commune->where('INSEE_COM', $insee)->firstorfail();
            $commune_opport->COMM_OPPORT = 1;
            $commune_opport->save();
            return redirect('/backoffice');
    }
    
}

   