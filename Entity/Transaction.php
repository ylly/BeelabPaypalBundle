<?php

namespace Beelab\PaypalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transaction.
 *
 * @ORM\MappedSuperclass
 */
#[ORM\MappedSuperclass]
abstract class Transaction
{
    public const STATUS_KO = -1;
    public const STATUS_STARTED = 0;
    public const STATUS_OK = 1;
    public const STATUS_ERROR = 2;

    public static $statuses = [
        self::STATUS_STARTED => 'started',
        self::STATUS_OK => 'success',
        self::STATUS_KO => 'canceled',
        self::STATUS_ERROR => 'failed',
    ];

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    protected int $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    #[ORM\Column(type: 'datetime')]
    protected \DateTime $start;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    protected ?\DateTime $end = null;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"default": 0})
     */
    #[ORM\Column(type: 'smallint', options: ['default' => 0])]
    protected int $status = self::STATUS_STARTED;

    /**
     * @var string
     *
     * @ORM\Column(unique=true)
     */
    #[ORM\Column(unique: true)]
    protected string $token;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=6, scale=2, options={"default": "0.00"})
     */
    #[ORM\Column(type: 'decimal', precision: 6, scale: 2, options: ['default' => '0.00'])]
    protected float $amount = 0.00;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    #[ORM\Column(type: 'array')]
    protected array $response;

    public function __construct($amount = null)
    {
        $this->amount = $amount;
        $this->start = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setStart(?\DateTime $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getStart(): ?\DateTime
    {
        return $this->start;
    }

    public function setEnd(?\DateTime $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getEnd(): ?\DateTime
    {
        return $this->end;
    }

    public function setStatus(?int $status): ?int
    {
        $this->status = $status;

        return $status;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function getStatusLabel(): string
    {
        return static::$statuses[$this->status] ?? 'invalid';
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function getResponse(): ?array
    {
        return $this->response;
    }

    public function complete(array $response): void
    {
        if (self::STATUS_OK !== $this->status) {
            $this->status = self::STATUS_OK;
            $this->end = new \DateTime();
            $this->response = $response;
        }
    }

    public function cancel(): void
    {
        $this->status = self::STATUS_KO;
        $this->end = new \DateTime();
    }

    public function error(array $response): void
    {
        $this->status = self::STATUS_ERROR;
        $this->end = new \DateTime();
        $this->response = $response;
    }

    public function isOk(): bool
    {
        return self::STATUS_OK === $this->status;
    }

    public function getDescription(): ?string
    {
        return null;
    }

    public function getItems(): array
    {
        return [];
    }

    public function getShippingAmount(): string
    {
        return '0.00';
    }
}
