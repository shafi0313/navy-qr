<?php

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;

if (! function_exists('bdDate')) {
    function bdDate($date)
    {
        return Carbon::parse($date)->format('d/m/Y');
    }
}

if (! function_exists('bdDateTime')) {
    function bdDateTime($date)
    {
        return Carbon::parse($date)->format('d/m/Y h:i A');
    }
}

if (! function_exists('stringToDate')) {
    function stringToDate($date)
    {
        // return Carbon::parse($date)->format('Y-m-d');
        // 'js_date_format'        => "dd/mm/yy",
        // 'frontend_date_format'  => 'd/m/Y',
        return Carbon::createFromFormat('d/m/Y', $date);
    }
}
if (! function_exists('sqlDate')) {
    function sqlDate($date)
    {
        if ($date) {
            return Carbon::parse($date)->format('Y-m-d');
        } else {
            return null;
        }
    }
}
if (! function_exists('result')) {
    function result($data, $remark = null)
    {
        if ($data == '1' || $data == '0') {
            $data = (int) $data;

            return match ($data) {
                1 => '<span class="btn btn-success btn-rem">Fit</span>',
                0 => '<span class="btn btn-danger btn-rem">Unfit </span> '.($remark ? '('.$remark.')' : ''),
            };
        } else {
            return '<span class="btn btn-warning btn-rem">Pending</span>';
        }
    }
}

if (! function_exists('ageWithDays')) {
    function ageWithDays($d_o_b)
    {
        return Carbon::parse($d_o_b)->diff(Carbon::now())->format('%y years, %m months and %d days');
    }
}
if (! function_exists('ageWithMonths')) {
    function ageWithMonths($d_o_b)
    {
        return Carbon::parse($d_o_b)->diff(Carbon::now())->format('%y years, %m months');
    }
}
if (! function_exists('strPad4')) {
    function strPad4($data)
    {
        return str_pad($data, 4, '0', STR_PAD_LEFT);
    }
}
if (! function_exists('strPad6')) {
    function strPad6($data)
    {
        if ($data) {
            return str_pad($data, 6, '0', STR_PAD_LEFT);
        } else {
            return '';
        }
    }
}

if (! function_exists('team')) {
    function team($team)
    {
        if ($team == 'a') {
            return [
                'rangpur',
                'nilphamari',
                'lalmonirhat',
                'kurigram',
                'dinajpur',
                'gaibandha',
                'panchagarh',
                'thakurgaon',
                'mymensingh',
                'netrakona',
                'kishoreganj',
                'jamalpur',
                'sherpur',
                'dhaka',
                'manikganj',
                'gazipur',
                'narsingdi',
                'munshiganj',
                'narayanganj',
                'tangail',
                'sylhet',
                'sunamganj',
                'habiganj',
                'moulvibazar',
            ]; // Dhaka
        } elseif ($team == 'b') {
            return [
                'rajshahi',
                'pabna',
                'sirajganj',
                'chapai-nawabgonj',
                'natore',
                'joypurhat',
                'bogura',
                'naogaon',
                'madaripur',
                'rajbari',
                'gopalganj',
                'shariatpur',
                'faridpur',
                'patuakhali',
                'bhola',
                'barguna',
                'barishal',
                'khulna',
                'pirojpur',
                'jhalokati',
                'bagerhat',
                'satkhira',
                'magura',
                'chuadanga',
                'jhenaidah',
                'meherpur',
                'jashore',
                'narail',
                'kushtia',
            ]; // Khulna
        } elseif ($team == 'c') {
            return [
                'cumilla',
                'chandpur',
                'lakshmipur',
                'feni',
                'noakhali',
                'brahmanbaria',
                'chattogram',
                'coxs-bazar',
                'khagrachhari',
                'rangamati',
                'bandarban',
            ]; // Chattogram
        }
    }
}

if (! function_exists('pad6')) {
    function pad6($number)
    {
        return str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}

if (! function_exists('nF')) {
    function nF($number)
    {
        return number_format($number, 0);
    }
}

if (! function_exists('nF2')) {
    function nF2($number)
    {
        return number_format($number, 2);
    }
}

if (! function_exists('nFA2')) {
    function nFA2($num)
    {
        return number_format(abs($num), 2);
    }
}

// if (!function_exists('carbon')) {
//     function carbon($d_o_b)
//     {
//         return Carbon::parse($d_o_b)->diff(Carbon::now())->format('%y years, %m months');
//     }
// }

/************************** Image **************************/

if (! function_exists('imgWebpStore')) {
    function imgWebpStore($image, string $path, ?array $size = null)
    {
        $image = Image::make($image);
        if ($size[0] && $size[1]) {
            $image->fit($size[0], $size[1]);
        }

        if ($size[0] && $size[1] == null) {
            $image->resize($size[0], null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $dir = public_path('/uploads/images/'.$path);
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $imageName = $path.'-'.uniqueId(10).'.webp';
        $image->encode('webp', 70)->save($dir.'/'.$imageName);

        return $imageName;
    }
}

if (! function_exists('imgWebpUpdate')) {
    function imgWebpUpdate($image, string $path, ?array $size, $oldImage)
    {
        $image = Image::make($image);
        if ($size[0] && $size[1]) {
            $image->fit($size[0], $size[1]);
        }

        if ($size[0] && $size[1] == null) {
            $image->resize($size[0], null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $dir = public_path('/uploads/images/'.$path);
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $imageName = $path.'-'.uniqueId(10).'.webp';
        $image->encode('webp', 70)->save($dir.'/'.$imageName);

        $checkPath = $dir.'/'.$oldImage;
        if ($oldImage && file_exists($checkPath)) {
            unlink($checkPath);
        }

        return $imageName;
    }
}
if (! function_exists('imgUnlink')) {
    function imgUnlink($folder, $image)
    {
        $path = public_path('uploads/images/'.$folder.'/'.$image);
        if ($image && file_exists($path)) {
            return unlink($path);
        }
    }
}

if (! function_exists('imageStore')) {
    function imageStore(Request $request, $request_name, string $name, string $path)
    {
        if ($request->hasFile($request_name)) {
            $pathCreate = public_path().'/uploads/images/'.$path.'/';
            ! file_exists($pathCreate) ?? File::makeDirectory($pathCreate, 0777, true, true);

            $image = $request->file($request_name);
            $imageName = $name.uniqueId(10).'.'.$image->getClientOriginalExtension();
            if ($image->isValid()) {
                $request->$request_name->move(public_path().'/uploads/images/'.$path.'/', $imageName);

                return $imageName;
            }
        }
    }
}

if (! function_exists('imageUpdate')) {
    function imageUpdate(Request $request, $request_name, string $name, string $path, $old_image)
    {
        if ($request->hasFile($request_name)) {
            $deletePath = public_path("uploads/images/{$path}/{$old_image}");

            if (! empty($old_image) && file_exists($deletePath)) {
                unlink($deletePath);
            }

            $createPath = public_path($path);
            if (! file_exists($createPath)) {
                File::makeDirectory($createPath, 0777, true, true);
            }

            $image = $request->file($request_name);
            $imageName = "{$name}_".uniqueId(10).'.'.$image->getClientOriginalExtension();

            if ($image->isValid()) {
                $image->move(public_path("uploads/images/{$path}/"), $imageName);

                return $imageName;
            }
        } else {
            return $old_image;
        }
    }
}

if (! function_exists('imagePath')) {
    function imagePath($folder, $image)
    {
        $path = 'uploads/images/'.$folder.'/'.$image;
        if (@getimagesize($path)) {
            return asset($path);
        } else {
            return asset('uploads/images/no-img.jpg');
        }
    }
}

if (! function_exists('profileImg')) {
    function profileImg()
    {
        if (file_exists(asset('uploads/images/user/'.user()->image))) {
            return asset('uploads/images/user/'.user()->image);
        } else {
            return asset('uploads/images/user/avatar.png');
            // if(user()->gender && user()->gender == 'Female'){
            //     return asset('uploads/images/user/female-blank.jpg');
            // }else{
            //     return asset('uploads/images/user/avatar.png');
            // }

        }
    }
}
/************************** !Image **************************/

if (! function_exists('gender')) {
    function gender(int $user)
    {
        return match ($user) {
            1 => 'Male',
            2 => 'Female',
            3 => 'Other',
            default => 'Unknown'
        };
    }
}

if (! function_exists('transaction_id')) {
    function transaction_id($src = '', $length = 12)
    {
        if (function_exists('random_bytes')) {
            $bytes = random_bytes(ceil($length / 2));
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
        } else {
            throw new \Exception('no cryptographically secure random function available');
        }
        if ($src != '') {
            return strtoupper($src.'_'.substr(bin2hex($bytes), 0, $length));
        }

        return strtoupper(substr(bin2hex($bytes), 0, $length));
    }
}

if (! function_exists('activeNav')) {
    function activeNav($route)
    {
        if (is_array($route)) {
            $rt = '';
            foreach ($route as $rut) {
                $rt .= request()->routeIs($rut) || '';
            }

            return $rt ? ' menuitem-active ' : '';
        }

        return request()->routeIs($route) ? ' menuitem-active ' : '';
    }
}

if (! function_exists('openNav')) {
    function openNav(array $routes)
    {
        $rt = '';
        foreach ($routes as $route) {
            $rt .= request()->routeIs($route) || '';
        }

        return $rt ? ' show ' : '';
    }
}

if (! function_exists('uniqueId')) {
    function uniqueId($lenght = 8)
    {
        if (function_exists('random_bytes')) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            throw new \Exception('no cryptographically secure random function available');
        }

        return substr(bin2hex($bytes), 0, $lenght);
    }
}

if (! function_exists('user')) {
    function user()
    {
        return Auth::user();
    }
}

if (! function_exists('sendOtpViaSms')) {
    function sendOtpViaSms($mobileNumber, $otp)
    {
        $apiKey = '943faf062f3d7241';
        $secretKey = 'dfd0b83b';
        $senderId = 'Navy_Barcode';
        $message = "Your OTP code is $otp.\nQR Code Apps \nBangladesh Navy";

        $response = Http::get('http://smpp.revesms.com:7788/sendtext', [
            'apikey' => $apiKey,
            'secretkey' => $secretKey,
            'callerID' => $senderId,
            'toUser' => $mobileNumber,
            'messageContent' => $message,
        ]);

        // Check the response to make sure the message was sent successfully
        if ($response->successful()) {
            return true;
        }

        return false;
    }
}
if (! function_exists('sendSms')) {
    function sendSms($mobileNumber, $msg)
    {
        $apiKey = '943faf062f3d7241';
        $secretKey = 'dfd0b83b';
        $senderId = 'Navy_Barcode';

        $response = Http::get('http://smpp.revesms.com:7788/sendtext', [
            'apikey' => $apiKey,
            'secretkey' => $secretKey,
            'callerID' => $senderId,
            'toUser' => $mobileNumber,
            'messageContent' => $msg,
        ]);

        // Check the response to make sure the message was sent successfully
        if ($response->successful()) {
            return true;
        }

        return false;
    }
}

if (! function_exists('written')) {
    function written($row)
    {
        if (($row->bangla || $row->english || $row->math || $row->science || $row->general_knowledge)) {
            $total_marks = $row->bangla + $row->english + $row->math + $row->science + $row->general_knowledge;
            $failCount = 0;
            // Check each subject mark and count fails
            if ($row->bangla < 8) {
                $failCount++;
            }
            if ($row->english < 8) {
                $failCount++;
            }
            if ($row->math < 8) {
                $failCount++;
            }
            if ($row->science < 8) {
                $failCount++;
            }
            if ($row->general_knowledge < 8) {
                $failCount++;
            }
            // If no subject failed and all marks are >= 8, it's a pass
            if ($failCount == 0) {
                return '<span class="badge bg-success">Pass</span>'.' ('.$total_marks.')';
            }
            // If there are any fails, it's a fail
            elseif ($failCount > 0) {
                return '<span class="badge bg-danger">Failed</span> ('.$failCount.' subject(s) failed)';
            } else {
                return 'Pending';
            }
        } else {
            return 'Pending';
        }
    }
}
