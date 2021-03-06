<?php 
namespace App;

use App\User;
use Auth;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Helpers {

	/**
	 * Status codes translation table.
	 *
	 * The list of codes is complete according to the
	 * {@link http://www.iana.org/assignments/http-status-codes/ Hypertext Transfer Protocol (HTTP) Status Code Registry}
	 * (last updated 2012-02-13).
	 *
	 * Unless otherwise noted, the status code is defined in RFC2616.
	 *
	 * @var array
	 */
	public $statusTexts = array(
	    100 => 'Continue',
	    101 => 'Switching Protocols',
	    102 => 'Processing',            // RFC2518
	    200 => 'OK',
	    201 => 'Created',
	    202 => 'Accepted',
	    203 => 'Non-Authoritative Information',
	    204 => 'No Content',
	    205 => 'Reset Content',
	    206 => 'Partial Content',
	    207 => 'Multi-Status',          // RFC4918
	    208 => 'Already Reported',      // RFC5842
	    226 => 'IM Used',               // RFC3229
	    300 => 'Multiple Choices',
	    301 => 'Moved Permanently',
	    302 => 'Found',
	    303 => 'See Other',
	    304 => 'Not Modified',
	    305 => 'Use Proxy',
	    306 => 'Reserved',
	    307 => 'Temporary Redirect',
	    308 => 'Permanent Redirect',    // RFC7238
	    400 => 'Bad Request',
	    401 => 'Unauthorized',
	    402 => 'Payment Required',
	    403 => 'Forbidden',
	    404 => 'Not Found',
	    405 => 'Method Not Allowed',
	    406 => 'Not Acceptable',
	    407 => 'Proxy Authentication Required',
	    408 => 'Request Timeout',
	    409 => 'Conflict',
	    410 => 'Gone',
	    411 => 'Length Required',
	    412 => 'Precondition Failed',
	    413 => 'Request Entity Too Large',
	    414 => 'Request-URI Too Long',
	    415 => 'Unsupported Media Type',
	    416 => 'Requested Range Not Satisfiable',
	    417 => 'Expectation Failed',
	    418 => 'I\'m a teapot',                                               // RFC2324
	    422 => 'Unprocessable Entity',                                        // RFC4918
	    423 => 'Locked',                                                      // RFC4918
	    424 => 'Failed Dependency',                                           // RFC4918
	    425 => 'Reserved for WebDAV advanced collections expired proposal',   // RFC2817
	    426 => 'Upgrade Required',                                            // RFC2817
	    428 => 'Precondition Required',                                       // RFC6585
	    429 => 'Too Many Requests',                                           // RFC6585
	    431 => 'Request Header Fields Too Large',                             // RFC6585
	    500 => 'Internal Server Error',
	    501 => 'Not Implemented',
	    502 => 'Bad Gateway',
	    503 => 'Service Unavailable',
	    504 => 'Gateway Timeout',
	    505 => 'HTTP Version Not Supported',
	    506 => 'Variant Also Negotiates (Experimental)',                      // RFC2295
	    507 => 'Insufficient Storage',                                        // RFC4918
	    508 => 'Loop Detected',                                               // RFC5842
	    510 => 'Not Extended',                                                // RFC2774
	    511 => 'Network Authentication Required',                             // RFC6585
	);

	public static function responseJson($success, $data = NULL, $message = NULL, $code=200) {
		return response()->json(array(
				'success'	=> $success,
				'data'		=> $data,
				'message'	=> $message,
			))->setStatusCode($code);
	}

	public static function responseFailed($message = NULL, $data = NULL, $code=400) {
		$instance = new Helpers;
		return $instance->responseJson($data, $message, false, $code);
	}


    public static function Response($request, $success, $data, $message, $code)
    {
        /*digunakan untuk route API */
        $rv = array(
                'success'   => $success,
                'data'      => $data,
                'message'   => $message,
            );        

        $js =  json_encode($rv);
        $rq =  json_encode($request->all());
        $url = $request->fullUrl();

        /* untuk menyimpan data request dan result api */
        /*
        T_Api_log::create([
                    'url' => $url,
                    'request' => $rq,
                    'result' => $js,
                    ]);
        */
                    
        return response()->json($rv)->setStatusCode($code);
    }
	
	public static function generateToken(){
		return Str::random(64); 
	}

	public static function rndstr($jumlah) {
		return Str::random($jumlah);
	}

	Public static function TokenValid(){
		$result = Carbon::now()->addMinutes(30);
		return $result;
	}

	public static function generateUpdated(){
		$result =  date('Y-m-d H:i:s')."|".Auth::id();
		return $result;
	}

	public static function getStatusCodeMessage($code){
		$instance = new Helpers;
		$statusTextsList = $instance->statusTexts;
		return $statusTextsList[$code];
	}


    public static function formCheckbox($id)
    {
        $form = '<input type="checkbox" name="ids[]" value="'.$id.'"/>';

        return $form;
    }

	public static function Pagination($Event, $jr, $item_per_halaman, $halaman) {
		
		$jhal = intval($jr / $item_per_halaman );
		if (($jr % $item_per_halaman) > 0) {
			$jhal = $jhal + 1;
		}
			
		$pagination = "<div class='btn-group'>";

		/* Halaman pertama*/
		if ($halaman > 2) {
			$pagination .= "<button type=\"button\" class=\"btn  btn-outline-info\" onclick=\"".$Event."(1)\" id='btnHalaman' ><i class=\"fas fa-fast-backward\"></i></button>";
		}

		/* Halaman Sebelumnya*/
		if ($halaman > 1) {
			$sebelumnya = $halaman - 1;
			$pagination .= "<button type=\"button\" class=\"btn  btn-outline-info\" onclick=\"".$Event."(".$sebelumnya.")\" id='btnHalaman' ><i class=\"fas fa-backward\"></i></button>";
		}
		
		/* 3 Halaman Sebelumnya*/
		if (($halaman - 3) >= 1) {
			$sebelumnya = $halaman - 3;
			$pagination .= "<button type=\"button\" class=\"btn  btn-outline-info\" onclick=\"".$Event."(".$sebelumnya.")\" id='btnHalaman' >$sebelumnya</button>";
		}
		/* 2 Halaman Sebelumnya*/
		if (($halaman - 2) >= 1) {
			$sebelumnya = $halaman - 2;
			$pagination .= "<button type=\"button\" class=\"btn  btn-outline-info\" onclick=\"".$Event."(".$sebelumnya.")\" id='btnHalaman' >$sebelumnya</button>";
		}
		/* 1 Halaman Sebelumnya*/
		if (($halaman - 1) >= 1) {
			$sebelumnya = $halaman - 1;
			$pagination .= "<button type=\"button\" class=\"btn  btn-outline-info\" onclick=\"".$Event."(".$sebelumnya.")\" id='btnHalaman' >$sebelumnya</button>";
		}
		/* Halaman Aktif*/
			$pagination .= "<button type=\"button\" class=\"btn btn-info disabled\" >$halaman</button>";
		
		/* 1 Halaman Setelahnya*/
		if (($halaman + 1) <= $jhal) {
			$setelahnya = $halaman + 1;
			$pagination .= "<button type=\"button\" class=\"btn  btn-outline-info\" onclick=\"".$Event."(".$setelahnya.")\" id='btnHalaman' >$setelahnya</button>";
		}
		/* 2 Halaman Setelahnya*/
		if (($halaman + 2) <= $jhal) {
			$setelahnya = $halaman + 2;
			$pagination .= "<button type=\"button\" class=\"btn  btn-outline-info\" onclick=\"".$Event."(".$setelahnya.")\" id='btnHalaman' >$setelahnya</button>";
		}
		/* 3 Halaman Setelahnya*/
		if (($halaman + 3) <= $jhal) {
			$setelahnya = $halaman + 3;
			$pagination .= "<button type=\"button\" class=\"btn  btn-outline-info\" onclick=\"".$Event."(".$setelahnya.")\" id='btnHalaman' >$setelahnya</button>";
		}
		
		/* Selanjutnya*/
		if (($halaman + 1) <= $jhal) {
			$setelahnya = $halaman + 1;
			$pagination .= "<button type=\"button\" class=\"btn  btn-outline-info\" onclick=\"".$Event."(".$setelahnya.")\" id='btnHalaman' ><i class=\"fas fa-forward\"></i></button>";
		}
		/* Halaman Terakhir*/
		if (($halaman + 2) <= $jhal) {
			$pagination .= "<button type=\"button\" class=\"btn  btn-outline-info\" onclick=\"".$Event."(".$jhal.")\" id='btnHalaman' ><i class=\"fas fa-fast-forward\"></i></button>";
			
		}
		
		$pagination .= "</div>";
		
		/*pagination */
		
		return $pagination;
	}

	public static function Warna() {
        $warna = Array();
        $warna['HIJAU']     = "btn-success";
        $warna['MERAH']     = "btn-danger";
        $warna['ORANGE']    = "btn-warning";
        $warna['BIRU']      = "btn-primary";
        $warna['BIRU MUDA'] = "btn-info";
        $warna['ABU-ABU'] 	= "btn-secondary";

        return $warna;
    }

}
