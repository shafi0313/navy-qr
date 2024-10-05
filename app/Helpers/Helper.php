<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

if (!function_exists('bdDate')) {
    function bdDate($date)
    {
        return Carbon::parse($date)->format('d/m/Y');
    }
}

if (!function_exists('stringToDate')) {
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
    function result($data)
    {
        return match ($data) {
            1 => '<span class="btn btn-success btn-sm">Fit</span>',
            0 => '<span class="btn btn-danger btn-sm">Unfit</span>',
            default => '<span class="btn btn-warning btn-sm">Pending</span>',
        };
    }
}

if (!function_exists('ageWithDays')) {
    function ageWithDays($d_o_b)
    {
        return Carbon::parse($d_o_b)->diff(Carbon::now())->format('%y years, %m months and %d days');
    }
}
if (!function_exists('ageWithMonths')) {
    function ageWithMonths($d_o_b)
    {
        return Carbon::parse($d_o_b)->diff(Carbon::now())->format('%y years, %m months');
    }
}
if (!function_exists('strPad4')) {
    function strPad4($data)
    {
        return str_pad($data, 4, '0', STR_PAD_LEFT);
    }
}
if (!function_exists('strPad6')) {
    function strPad6($data)
    {
        if ($data) {
            return str_pad($data, 6, '0', STR_PAD_LEFT);
        } else {
            return '';
        }
    }
}

if (!function_exists('pad6')) {
    function pad6($number)
    {
        return str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('nF2')) {
    function nF2($number)
    {
        return number_format($number, 2);
    }
}

if (!function_exists('nFA2')) {
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

if (!function_exists('imgWebpStore')) {
    function imgWebpStore($image, string $path, array $size = null)
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

        $dir = public_path('/uploads/images/' . $path);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $imageName = $path . '-' . uniqueId(10) . '.webp';
        $image->encode('webp', 70)->save($dir . '/' . $imageName);
        return $imageName;
    }
}

if (!function_exists('imgWebpUpdate')) {
    function imgWebpUpdate($image, string $path, array $size = null, $oldImage)
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

        $dir = public_path('/uploads/images/' . $path);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $imageName = $path . '-' . uniqueId(10) . '.webp';
        $image->encode('webp', 70)->save($dir . '/' . $imageName);

        $checkPath =  $dir . '/' . $oldImage;
        if ($oldImage && file_exists($checkPath)) {
            unlink($checkPath);
        }
        return $imageName;
    }
}
if (!function_exists('imgUnlink')) {
    function imgUnlink($folder, $image)
    {
        $path = public_path('uploads/images/' . $folder . '/' . $image);
        if ($image && file_exists($path)) {
            return unlink($path);
        }
    }
}


if (!function_exists('imageStore')) {
    function imageStore(Request $request, $request_name, string $name, string $path)
    {
        if ($request->hasFile($request_name)) {
            $pathCreate = public_path() . '/uploads/images/' . $path . '/';
            !file_exists($pathCreate) ?? File::makeDirectory($pathCreate, 0777, true, true);

            $image = $request->file($request_name);
            $imageName = $name . uniqueId(10) . '.' . $image->getClientOriginalExtension();
            if ($image->isValid()) {
                $request->$request_name->move(public_path() . '/uploads/images/' . $path . '/', $imageName);
                return $imageName;
            }
        }
    }
}

if (!function_exists('imageUpdate')) {
    function imageUpdate(Request $request, $request_name, string $name, string $path, $old_image)
    {
        if ($request->hasFile($request_name)) {
            $deletePath = public_path("uploads/images/{$path}/{$old_image}");

            if (!empty($old_image) && file_exists($deletePath)) {
                unlink($deletePath);
            }

            $createPath = public_path($path);
            if (!file_exists($createPath)) {
                File::makeDirectory($createPath, 0777, true, true);
            }

            $image = $request->file($request_name);
            $imageName = "{$name}_" . uniqueId(10) . '.' . $image->getClientOriginalExtension();

            if ($image->isValid()) {
                $image->move(public_path("uploads/images/{$path}/"), $imageName);
                return $imageName;
            }
        } else {
            return $old_image;
        }
    }
}

if (!function_exists('imagePath')) {
    function imagePath($folder, $image)
    {
        $path = 'uploads/images/' . $folder . '/' . $image;
        if (@GetImageSize($path)) {
            return asset($path);
        } else {
            return asset('uploads/images/no-img.jpg');
        }
    }
}

if (!function_exists('profileImg')) {
    function profileImg()
    {
        if (file_exists(asset('uploads/images/user/' . user()->image))) {
            return asset('uploads/images/user/' . user()->image);
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

if (!function_exists('gender')) {
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

if (!function_exists('transaction_id')) {
    function transaction_id($src = '', $length = 12)
    {
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($length / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
        } else {
            throw new \Exception("no cryptographically secure random function available");
        }
        if ($src != '') {
            return strtoupper($src . '_' . substr(bin2hex($bytes), 0, $length));
        }
        return strtoupper(substr(bin2hex($bytes), 0, $length));
    }
}

if (!function_exists('activeNav')) {
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

if (!function_exists('openNav')) {
    function openNav(array $routes)
    {
        $rt = '';
        foreach ($routes as $route) {
            $rt .= request()->routeIs($route) || '';
        }
        return $rt ? ' show ' : '';
    }
}
if (!function_exists('userCan')) {
    function userCan($permission)
    {
        if (auth()->check() && user()->can($permission)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('roleName')) {
    function roleName($roles)
    {
        $r = '';
        foreach ($roles->where('name', '!=', 'client') as $role) {
            $r .= '<span class="badge badge-info">' . ucfirst(explode('@uid', $role->name)[0]) . '</span>';
        }
        return $r;
    }
}
if (!function_exists('trimRoleAdmin')) {
    function trimRoleAdmin($roles)
    {
        return str_replace('-', ' ', $roles);
    }
}
if (!function_exists('trimRole')) {
    function trimRole($val)
    {
        return str_replace('-', ' ', explode('@uid', $val)[0]);
    }
}


if (!function_exists('uniqueId')) {
    function uniqueId($lenght = 8)
    {
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            throw new \Exception("no cryptographically secure random function available");
        }
        return substr(bin2hex($bytes), 0, $lenght);
    }
}

if (!function_exists('user')) {
    function user()
    {
        return auth()->user();
    }
}

if (! function_exists('slug')) {
    function slug($text)
    {
        $array = [':', ',', '.', '!', '|', '।', 'ঃ', '{', '}', '[', ']', '(', ')', '৳', '%', '$', '#', '@', '*', '+', ';'];
        $slug = strtolower(str_replace($array, '', trim($text)));
        return str_replace(' ', '-', $slug);
        return strtolower(preg_replace('/\s+/u', '-', trim($text)));
    }
}


if (!function_exists('readableSize')) {
    function readableSize($n)
    {
        // first strip any formatting;
        $n = (0 + str_replace(",", "", $n));

        // is this a number?
        if (!is_numeric($n)) {
            return $n;
        }
        // now filter it;
        if ($n >= 1000000000000) {
            return round(($n / 1000000000000), 1) . ' TB';
        } elseif ($n >= 1000000000) {
            return round(($n / 1000000000), 1) . ' GB';
        } elseif ($n >= 1000000) {
            return round(($n / 1000000), 1) . ' MB';
        } elseif ($n >= 1000) {
            return round(($n / 1000), 1) . ' KB';
        }
        return number_format($n);
    }
}

if (!function_exists('niceFileSize')) {
    function niceFileSize($bytes)
    {
        $result = '';
        $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

        foreach ($arBytes as $arItem) {
            if ($bytes >= $arItem["VALUE"]) {
                $result = $bytes / $arItem["VALUE"];
                $result = strval(round($result, 2)) . " " . $arItem["UNIT"];
                break;
            }
        }
        return $result;
    }

    /**
     * Converts a number to its roman presentation.
     **/
    if (!function_exists('numberToRoman')) {
        function numberToRoman($num)
        {
            // Be sure to convert the given parameter into an integer
            $n = intval($num);
            $result = '';
            // Declare a lookup array that we will use to traverse the number:
            $lookup = array(
                'M' => 1000,
                'CM' => 900,
                'D' => 500,
                'CD' => 400,
                'C' => 100,
                'XC' => 90,
                'L' => 50,
                'XL' => 40,
                'X' => 10,
                'IX' => 9,
                'V' => 5,
                'IV' => 4,
                'I' => 1
            );
            foreach ($lookup as $roman => $value) {
                // Look for number of matches
                $matches = intval($n / $value);
                // Concatenate characters
                $result .= str_repeat($roman, $matches);
                // Substract that from the number
                $n = $n % $value;
            }
            return $result;
        }
    };

    if (!function_exists('numberNotation')) {
        function numberNotation($number, $precision = 1)
        {
            $suffixes = ['', 'K', 'M', 'B', 'T'];
            $suffixIndex = 0;
            while ($number >= 900 && $suffixIndex < count($suffixes) - 1) {
                $number /= 1000;
                $suffixIndex++;
            }
            $formattedNumber = number_format($number, $precision);
            if ($precision > 0) {
                $formattedNumber = rtrim($formattedNumber, '0');
                $formattedNumber = rtrim($formattedNumber, '.');
            }
            return $formattedNumber . $suffixes[$suffixIndex];
        }
    }
}
