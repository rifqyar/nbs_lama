<?php
$noreq = $_GET['no_req'];
$jn = $_GET['jn'];

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
?>