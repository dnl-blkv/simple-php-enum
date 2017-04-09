<?php

namespace dnl_blkv\enum;

use Closure;

/**
 */
class EnumMapBuilder
{
    /**
     * The prefix we use to access a constant which is defined in the Enum class.
     */
    const PATTERN_CLASS_CONSTANT = '%s::%s';

    /**
     * Default value for enum ordinal.
     */
    const ENUM_ORDINAL_DEFAULT = 0;

    /**
     * @var mixed[]
     */
    protected $constantMap;

    /**
     * @var Closure
     */
    protected $createEnumInstance;

    /**
     * @var Enum[]
     */
    protected $nameToInstanceMap = [];

    /**
     * @var Enum[]
     */
    protected $ordinalToInstanceMap = [];

    /**
     * @var int
     */
    protected $lastOrdinal;

    /**
     * @param mixed[] $constantMap
     * @param Closure $createEnumInstance Signature must be $createEnumInstance(string $name, int $ordinal).
     */
    public function __construct(array $constantMap, Closure $createEnumInstance)
    {
        $this->constantMap = $constantMap;
        $this->createEnumInstance = $createEnumInstance;
        $this->initializeEnumMaps();
    }

    /**
     */
    protected function initializeEnumMaps()
    {
        $this->resetLastOrdinal();

        foreach ($this->constantMap as $name => $constantValue) {
            if (EnumLib::isValidEnumConstantName($name)) {
                $nextOrdinal = $this->getNextOrdinal($constantValue);
                $this->addNameOrdinalPairToMaps($name, $nextOrdinal);
                $this->updateLastOrdinal($nextOrdinal);
            }
        }
    }

    /**
     */
    protected function resetLastOrdinal()
    {
        $this->lastOrdinal = self::ENUM_ORDINAL_DEFAULT - 1;
    }

    /**
     * @param int|null $constantValue
     *
     * @return int
     */
    protected function getNextOrdinal(int $constantValue = null): int
    {
        return is_null($constantValue) ? $this->lastOrdinal + 1 : $constantValue;
    }

    /**
     * @param string $name
     * @param int $ordinal
     */
    protected function addNameOrdinalPairToMaps(string $name, int $ordinal)
    {
        $instance = ($this->createEnumInstance)($name, $ordinal);
        $this->addNameToInstanceMapping($name, $instance);
        $this->addOrdinalToInstanceMapping($ordinal, $instance);
    }

    /**
     * @param string $name
     * @param mixed $instance An instance of the enum. It is not strictly-typed on purpose. Here we are taking advantage
     *                        of the fact that PHP is a dynamically-typed language to keep the EnumMapBuilder testable
     *                        and completely decoupled from any particular enum implementation.
     */
    protected function addNameToInstanceMapping(string $name, $instance)
    {
        $this->nameToInstanceMap[$name] = $instance;
    }

    /**
     * @param int $ordinal
     * @param mixed $instance An instance of the enum. It is not strictly-typed on purpose. Here we are taking advantage
     *                        of the fact that PHP is a dynamically-typed language to keep the EnumMapBuilder testable
     *                        and completely decoupled from any particular enum implementation.
     */
    protected function addOrdinalToInstanceMapping(int $ordinal, $instance)
    {
        if (!isset($this->ordinalToInstanceMap[$ordinal])) {
            $this->ordinalToInstanceMap[$ordinal] = [];
        }

        $this->ordinalToInstanceMap[$ordinal][] = $instance;
    }

    /**
     * @param int $lastOrdinalNew
     */
    protected function updateLastOrdinal(int $lastOrdinalNew)
    {
        $this->lastOrdinal = $lastOrdinalNew;
    }

    /**
     * @return Enum[]
     */
    public function getNameToInstanceMap(): array
    {
        return $this->nameToInstanceMap;
    }

    /**
     * @return Enum[]
     */
    public function getOrdinalToInstanceMap(): array
    {
        return $this->ordinalToInstanceMap;
    }
}
