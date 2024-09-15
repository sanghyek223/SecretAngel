<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class CryptoDecrypt
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->ajax()) {
            // 데이터를 복호화
            foreach ($request->all() as $key => $val) {
                $request[$key] = $this->cryptoDecrypt($val);
            }
        }

        return $next($request);
    }

    private function cryptoDecrypt($val)
    {
        $password = 'secret phrase';

        if (is_array($val)) {
            // 배열의 각 요소를 개별적으로 복호화
            foreach ($val as &$item) {
                $item = $this->cryptoDecrypt($item);
            }
        }

        if (is_string($val) && base64_decode($val, true) !== false) {
            $cipherText = base64_decode($val);

            if (substr($cipherText, 0, 8) !== "Salted__") {
                abort(500);
            }

            $salt = substr($cipherText, 8, 8);
            $keyAndIV = $this->evpKDF($password, $salt);

            return openssl_decrypt(
                substr($cipherText, 16),
                "aes-256-cbc",
                $keyAndIV["key"],
                OPENSSL_RAW_DATA, // base64 was already decoded
                $keyAndIV["iv"]
            );
        }

        return $val; // 다른 타입의 데이터는 복호화하지 않음
    }

    private function evpKDF(string $password, string $salt, $keySize = 8, $ivSize = 4, $iterations = 1, $hashAlgorithm = "md5")
    {
        $targetKeySize = $keySize + $ivSize;
        $derivedBytes = "";
        $numberOfDerivedWords = 0;
        $block = NULL;
        $hasher = hash_init($hashAlgorithm);

        while ($numberOfDerivedWords < $targetKeySize) {
            if (!empty($block)) {
                hash_update($hasher, $block);
            }

            hash_update($hasher, $password);
            hash_update($hasher, $salt);

            $block = hash_final($hasher, true);
            $hasher = hash_init($hashAlgorithm);

            // Iterations
            for ($i = 1; $i < $iterations; $i++) {
                hash_update($hasher, $block);
                $block = hash_final($hasher, true);
                $hasher = hash_init($hashAlgorithm);
            }

            $derivedBytes .= substr($block, 0, min(strlen($block), ($targetKeySize - $numberOfDerivedWords) * 4));
            $numberOfDerivedWords += strlen($block) / 4;
        }

        return [
            "key" => substr($derivedBytes, 0, $keySize * 4),
            "iv" => substr($derivedBytes, $keySize * 4, $ivSize * 4)
        ];
    }
}

