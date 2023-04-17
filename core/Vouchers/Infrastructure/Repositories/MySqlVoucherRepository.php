<?php

namespace Core\Vouchers\Infrastructure\Repositories;

use Carbon\Carbon;
use Core\Vouchers\Domain\Entities\ValueObjects\VoucherContent;
use Core\Vouchers\Domain\Entities\ValueObjects\VoucherID;
use Core\Vouchers\Domain\Entities\ValueObjects\VoucherType;
use Core\Vouchers\Domain\Entities\Voucher;
use Core\Vouchers\Domain\Repositories\VoucherRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class MySqlVoucherRepository implements VoucherRepository
{
    public function store(Voucher $voucher): void
    {
        DB::table('vouchers')->insert($voucher->toArray());
    }

    /**
     * @param  VoucherID $voucherID
     * @return Voucher
     * @throws Exception
     */
    public function getByVoucherID(VoucherID $voucherID): Voucher
    {
        $voucher = DB::table('vouchers')->find($voucherID->value);

        if (is_null($voucher)) {
            throw new Exception('voucher not found');
        }

        return new Voucher(
            new VoucherID($voucher->id),
            new VoucherType($voucher->type),
            new VoucherContent(json_decode($voucher->content, true)),
            Carbon::parse($voucher->created_at),
            Carbon::parse($voucher->updated_at),
        );
    }

    /**
     * @param int $page
     * @param int|null $perPage
     * @return Voucher[]
     */
    public function getVouchers(int $page, ?int $perPage = null): array
    {
        if (is_null($perPage)) {
            return DB::table('vouchers')
                ->get()
                ->reduce(function (array &$vouchers, $voucher) {
                    $vouchers[] = new Voucher(
                        new VoucherID($voucher->id),
                        new VoucherType($voucher->type),
                        new VoucherContent(json_decode($voucher->content, true)),
                        Carbon::parse($voucher->created_at),
                        Carbon::parse($voucher->updated_at),
                    );
                }, []);

        }

        $vouchers = DB::table('vouchers')
            ->paginate($perPage, '*', 'page', $page)
            ->items();

        if (empty($vouchers)) {
            return [];
        }

        return collect($vouchers)->reduce(function (array &$vouchers, $voucher) {
                $vouchers[] = new Voucher(
                    new VoucherID($voucher->id),
                    new VoucherType($voucher->type),
                    new VoucherContent(json_decode($voucher->content, true)),
                    Carbon::parse($voucher->created_at),
                    Carbon::parse($voucher->updated_at),
                );

                return $vouchers;
            }, []);
    }
}
