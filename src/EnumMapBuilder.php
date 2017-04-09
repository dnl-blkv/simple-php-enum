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
     * @var string
     */
    protected $enumClassBase;

    /**
     * @var mixed[]
     */
    protected $constantMap;

    /**
     * @var Enum[]
     */
    protected $nameToInstanceMap;

    /**
     * @var Enum[]
     */
    protected $ordinalToInstanceMap;

    /**
     * @var int
     */
    protected $lastOrdinal;

    /**
     * @param string $enumClassBase
     * @param mixed[] $constantMap
     * @param Closure $createEnumInstance
     */
    public function __construct(string $enumClassBase, array $constantMap, Closure $createEnumInstance)
    {
        $this->enumClassBase = $enumClassBase;
        $this->constantMap = $constantMap;
        $this->nameToInstanceMap = $this->createNameToInstanceMap($createEnumInstance);
        $this->ordinalToInstanceMap = $this->createOrdinalToInstanceMap($this->nameToInstanceMap);
    }

    /**
     * @param Closure $createEnumInstance
     *
     * @return Enum[]
     */
    protected function createNameToInstanceMap(Closure $createEnumInstance): array
    {
        $nameToInstanceMap = [];

        $this->resetLastOrdinal();

        foreach ($this->constantMap as $name => $constantValue) {
            if ($this->isValidEnumConstant($name)) {
                $nextOrdinal = $this->getNextOrdinal($constantValue);
                $nameToInstanceMap[$name] = $createEnumInstance($name, $nextOrdinal);
                $this->updateLastOrdinal($nextOrdinal);
            }
        }

        return $nameToInstanceMap;
    }

    /**
     */
    protected function resetLastOrdinal()
    {
        $this->lastOrdinal = self::ENUM_ORDINAL_DEFAULT - 1;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function isValidEnumConstant(string $name): bool
    {
        return $this->isEnumConstant($name) && EnumLib::isValidEnumConstantName($name);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function isEnumConstant(string $name): bool
    {
        return !$this->isConstantDefinedInBaseEnum($name);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function isConstantDefinedInBaseEnum(string $name): bool
    {
        return defined($this->getConstantNameInBaseClass($name));
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getConstantNameInBaseClass(string $name): string
    {
        return sprintf(self::PATTERN_CLASS_CONSTANT, $this->enumClassBase, $name);
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
     * @param int $lastOrdinalNew
     */
    protected function updateLastOrdinal(int $lastOrdinalNew)
    {
        $this->lastOrdinal = $lastOrdinalNew;
    }

    /**
     * @param Enum[] $nameToInstanceMap
     *
     * @return Enum[]
     */
    protected function createOrdinalToInstanceMap(array $nameToInstanceMap): array
    {
        $ordinalToInstanceMap = [];

        foreach ($nameToInstanceMap as $instance) {
            $ordinal = $instance->getOrdinal();

            if (!isset($ordinalToInstanceMap[$ordinal])) {
                $ordinalToInstanceMap[$ordinal] = [];
            }

            $ordinalToInstanceMap[$ordinal][] = $instance;
        }

        return $ordinalToInstanceMap;
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
