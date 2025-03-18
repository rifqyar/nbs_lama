<?php
$noreq = $_GET['no_req'];
$jn = $_GET['jn'];
// firman 20 agustus 2020
$tgl = $_GET['tgl'];
//print_r($tgl); die();
$tgl_new=strtotime($tgl);
//print_r($tgl_new);die();

if ($tgl_new >= 1601398800) {
    //print_r('MTI');die();

        if($jn == 'RECEIVING'){
            header('Location: '.HOME.APPID.'/print_nota_lunas_rec_mti?no_req='.$noreq);
        }
        else if($jn == 'STRIPPING'){
            header('Location: '.HOME.APPID.'/print_nota_lunas_strip_mti?no_req='.$noreq);
        }
        else if($jn == 'RELOK_MTY'){
            header('Location: '.HOME.APPID.'/print_nota_lunas_relokmty_mti?no_req='.$noreq);
        }
        else if($jn == 'PERP_STRIP'){
            header('Location: '.HOME.APPID.'/print_nota_lunas_perpstrip_mti?no_req='.$noreq);
        }
        else if($jn == 'STUF_PNK'){
            header('Location: '.HOME.APPID.'/print_nota_lunas_pnknstuf_mti?no_req='.$noreq);
        }
        else if($jn == 'STUFFING'){
            header('Location: '.HOME.APPID.'/print_nota_lunas_stuf_mti?no_req='.$noreq);
        }
        else if($jn == 'DELIVERY'){
            $db = getDB('storage');
            $q = "select delivery_ke, no_req_ict from request_delivery where no_request = '$noreq'";
            $r = $db->query($q)->fetchRow();
            if($r['DELIVERY_KE'] == 'TPK'){
                header('Location: '.HOME.APPID.'/print_nota_lunas_deltpk_mti?no_req='.$noreq);   
            }
            else{
                header('Location: '.HOME.APPID.'/print_nota_lunas_deluar_mti?no_req='.$noreq);   
            }
            
        }
        else if($jn == 'PERP_DEV'){
            header('Location: '.HOME.APPID.'/print_nota_lunas_perpdel_mti?no_req='.$noreq);
        }
        else if($jn == 'PERP_PNK'){
            header('Location: '.HOME.APPID.'/print_nota_lunas_perpstuf_mti?no_req='.$noreq);
        }
        else if($jn == 'BATAL_MUAT'){
            header('Location: '.HOME.APPID.'/print_nota_lunas_bamu_mti?no_req='.$noreq);
        }
        else if($jn == 'DEL_PNK'){
            header('Location: '.HOME.APPID.'/print_nota_lunas_pnkndel_mti?no_req='.$noreq);
        }
}else{
    //print_r('LAMA');die();
                if($jn == 'RECEIVING'){
            header('Location: '.HOME.APPID.'/print_nota_lunas_rec?no_req='.$noreq);
        }
        else if($jn == 'STRIPPING'){
            header('Location: '.HOME.APPID.'/print_nota_lunas_strip?no_req='.$noreq);
        }
        else if($jn == 'RELOK_MTY'){
            header('Location: '.HOME.APPID.'/print_nota_lunas_relokmty?no_req='.$noreq);
        }
        else if($jn == 'PERP_STRIP'){
            header('Location: '.HOME.APPID.'/print_nota_lunas_perpstrip?no_req='.$noreq);
        }
        else if($jn == 'STUF_PNK'){
            header('Location: '.HOME.APPID.'/print_nota_lunas_pnknstuf?no_req='.$noreq);
        }
        else if($jn == 'STUFFING'){
            header('Location: '.HOME.APPID.'/print_nota_lunas_stuf?no_req='.$noreq);
        }
        else if($jn == 'DELIVERY'){
            $db = getDB('storage');
            $q = "select delivery_ke, no_req_ict from request_delivery where no_request = '$noreq'";
            $r = $db->query($q)->fetchRow();
            if($r['DELIVERY_KE'] == 'TPK'){
                header('Location: '.HOME.APPID.'/print_nota_lunas_deltpk?no_req='.$noreq);   
            }
            else{
                header('Location: '.HOME.APPID.'/print_nota_lunas_deluar?no_req='.$noreq);   
            }
            
        }
        else if($jn == 'PERP_DEV'){
            header('Location: '.HOME.APPID.'/print_nota_lunas_perpdel?no_req='.$noreq);
        }
        else if($jn == 'PERP_PNK'){
            header('Location: '.HOME.APPID.'/print_nota_lunas_perpstuf?no_req='.$noreq);
        }
        else if($jn == 'BATAL_MUAT'){
            header('Location: '.HOME.APPID.'/print_nota_lunas_bamu?no_req='.$noreq);
        }
        else if($jn == 'DEL_PNK'){
            header('Location: '.HOME.APPID.'/print_nota_lunas_pnkndel?no_req='.$noreq);
        }   
}
?>