<?php

declare(strict_types=1);

/*
 * This file is part of Passphrase.
 *
 * (c) Konceiver Oy <info@konceiver.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Konceiver\Passphrase;

final class BIP39
{
    private array $wordList;

    private function __construct(string $wordList)
    {
        $this->wordList = explode("\n", file_get_contents(realpath(__DIR__.'/bip39/'.$wordList.'.txt')));
    }

    public static function useChineseSimplified(): self
    {
        return new static('chinese_simplified');
    }

    public static function useChineseTraditional(): self
    {
        return new static('chinese_traditional');
    }

    public static function useCzech(): self
    {
        return new static('czech');
    }

    public static function useEnglish(): self
    {
        return new static('english');
    }

    public static function useFrench(): self
    {
        return new static('french');
    }

    public static function useItalian(): self
    {
        return new static('italian');
    }

    public static function useJapanese(): self
    {
        return new static('japanese');
    }

    public static function useKorean(): self
    {
        return new static('korean');
    }

    public static function useSpanish(): self
    {
        return new static('spanish');
    }

    public function generate(int $wordCount): string
    {
        return collect($this->generateEntropy($wordCount))
            ->map(fn (string $chunk) => $this->wordList[bindec($chunk)])
            ->implode(' ');
    }

    private function generateEntropy(int $wordCount): array
    {
        $overallBits  = $wordCount * 11;
        $checksumBits = (($wordCount - 12) / 3) + 4;
        $entropyBits  = $overallBits - $checksumBits;
        $entropy      = bin2hex(random_bytes($entropyBits / 8));
        $checksum     = $this->checksum($entropy, $checksumBits);

        return str_split($this->hex2bits($entropy).$checksum, 11);
    }

    private function hex2bits(string $hex): string
    {
        $bits = '';

        for ($i = 0; $i < strlen($hex); $i++) {
            $bits .= str_pad(base_convert($hex[$i], 16, 2), 4, '0', STR_PAD_LEFT);
        }

        return $bits;
    }

    private function checksum(string $entropy, int $bits): string
    {
        $checksumChar = ord(hash('sha256', hex2bin($entropy), true)[0]);
        $checksum     = '';

        for ($i = 0; $i < $bits; $i++) {
            $checksum .= $checksumChar >> (7 - $i) & 1;
        }

        return $checksum;
    }
}
