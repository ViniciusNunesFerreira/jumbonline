<?php

namespace App\Enums;

enum ShippingServices: string
{
    case SEDEX_CONTRATO_AG = '03220';
    case PAC_CONTRATO_AG = '03298';
    case APIPRECOS = '38202';
    case APIPRAZOS = '38210';

    /**
     * Códigos de varejo (tabela de balcão / cliente final). Usados SOMENTE
     * como referência de preço ao cliente na API de Preço — nunca para criar
     * pré-postagem, que continua sempre no código de contrato. Medição de
     * 01/10/2026 (frete:analise-comercial): com o token do contrato, estes
     * códigos devolvem a tabela cheia, enquanto 03050/03085 devolvem o mesmo
     * valor do contrato.
     */
    case SEDEX_VAREJO = '04014';
    case PAC_VAREJO = '04510';

    public function label(): string
    {
        return match ($this) {
            self::SEDEX_CONTRATO_AG => '03220',
            self::PAC_CONTRATO_AG => '03298',
            self::APIPRECOS => '38202',
            self::APIPRAZOS => '38210',
            self::SEDEX_VAREJO => '04014',
            self::PAC_VAREJO => '04510',
        };
    }

    /**
     * Código de varejo equivalente ao serviço de contrato, para consultar o
     * preço de balcão do mesmo envio. Null quando não há equivalente.
     */
    public function varejo(): ?self
    {
        return match ($this) {
            self::SEDEX_CONTRATO_AG, self::SEDEX_VAREJO => self::SEDEX_VAREJO,
            self::PAC_CONTRATO_AG, self::PAC_VAREJO => self::PAC_VAREJO,
            default => null,
        };
    }
}