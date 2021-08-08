<?PHP
    class Date
    {
        
        public function date_datediff( $str_interval, $dt_menor, $dt_maior, $relative=false){

            if( is_string( $dt_menor)) $dt_menor = date_create( $dt_menor);
            if( is_string( $dt_maior)) $dt_maior = date_create( $dt_maior);
            //date_add($dt_menor,date_interval_create_from_date_string("1 days"));
            $diff = date_diff( $dt_menor, $dt_maior,  $relative);

            switch( $str_interval){
                case "y":
                    $total = $diff->y + $diff->m / 12 + $diff->d / 365.25; break;
                case "m":
                    $total= $diff->y * 12 + $diff->m + $diff->d/30 + $diff->h / 24;
                    break;
                case "d":
                    $total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h/24 + $diff->i / 60;
                    //$total = (strtotime($dt_menor) - strtotime($dt_maior))/  ( 60 * 60 * 24 );  // 1 day = 60*60*24
                    break;
                case "h":
                    $total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60;
                    break;
                case "i":
                    $total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s/60;
                    break;
                case "s":
                    $total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i)*60 + $diff->s;
                    break;
                case "hire":
                    $total = $diff->d . " วัน , ".$diff->m." เดือน , ".$diff->y." ปี";
                    break;
                 case "diff":
                    $total = $diff;
                    break;
               }
            if( $diff->invert)
                    return -1 * $total;
            else    return $total;
        }
        function DateDiff($strDate1,$strDate2,$add=0)
        {
            return ((strtotime($strDate2) - strtotime($strDate1))/  ( 60 * 60 * 24 )+$add);  // 1 day = 60*60*24
        }
        function DateDiffUp($strDate1,$strDate2,$add=0)
        {
            return ((strtotime($strDate2) - strtotime($strDate1))/  ( 60 * 60 * 24 )+$add);  // 1 day = 60*60*24
        }
        public function check_formatdate($date){
            //$date="2012-09-12";
            if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)){
                return true;
            } else {
                return false;
            }
        }
        public function end_contract($dt_menor, $dt_maior,$relative=false){
            if( is_string( $dt_menor)) $dt_menor = date_create( $dt_menor);
            if( is_string( $dt_maior)) $dt_maior = date_create( $dt_maior);
            $diff = date_diff( $dt_menor, $dt_maior,  $relative);
            if($dt_menor<=$dt_maior){
                $total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h/24 + $diff->i / 60;
            }else{
                $total = -1;
            }

            return $total;
        }
        // public function get_datetime_diff($datetime_start,$datetime_end)
        // {
        //     $seconds = strtotime($datetime_end) - strtotime($datetime_start);

        //     $days    = floor($seconds / 86400);
        //     $hours   = floor(($seconds - ($days * 86400)) / 3600);
        //     $minutes = floor(($seconds - ($days * 86400) - ($hours * 3600))/60);
        //     $seconds = floor(($seconds - ($days * 86400) - ($hours * 3600) - ($minutes*60)));
        //     $str = '';
        //     if($days>=0){
        //         $str .= $days." Days ";
        //     }
        //     if($hours>=0){
        //         $str .= $hours." Hours ";
        //     }
        //     if($minutes>0){
        //         $str .= $minutes." Minutes ";
        //     }
        //     return $str.$seconds." Seconds";
        // }
        public function get_datetime_diff($mode = '',$datetime_start,$datetime_end)
        {
            $seconds = strtotime($datetime_end) - strtotime($datetime_start);

            $days    = floor($seconds / 86400);
            $hours   = floor(($seconds - ($days * 86400)) / 3600);
            $minutes = floor(($seconds - ($days * 86400) - ($hours * 3600))/60);
            $seconds = floor(($seconds - ($days * 86400) - ($hours * 3600) - ($minutes*60)));
            $str = '';
            if($days>=0){
                $str .= $days." Days ";
            }
            if($hours>=0){
                $str .= $hours." Hours ";
            }
            if($minutes>0){
                $str .= $minutes." Minutes ";
            }
            if($mode==""){
            	return $str.$seconds." Seconds";
            }else{
            	switch ($mode) {
            		case 'D':
            		case 'd':
            			return $days;
            			break;
            		case 'h:i:s':
            		case 'H:i:s':
            		case 'H:I:S':
            			return $hours.":".$minutes.":".$seconds;
            			break;            		
            		case 'H':
            		case 'h':
            			return $hours;
            			break;
            		case 'I':
            		case 'i':
            			return $minutes;
            			break;
            		case 'S':
            		case 's':
            			return $seconds;
            			break;
            		default:
            			# code...
            			break;
            	}
            }
            
        }

    }

?>