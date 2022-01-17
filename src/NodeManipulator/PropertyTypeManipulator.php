<?php

declare(strict_types=1);

namespace Rector\Doctrine\NodeManipulator;

use PhpParser\Node\Stmt\Property;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Core\Exception\NotImplementedYetException;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PhpDoc\NodeAnalyzer\DocBlockClassRenamer;
use Rector\NodeTypeResolver\ValueObject\OldToNewType;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;

final class PropertyTypeManipulator
{
    public function __construct(
        private readonly DocBlockClassRenamer $docBlockClassRenamer,
        private readonly PhpDocInfoFactory $phpDocInfoFactory
    ) {
    }

    public function changePropertyType(Property $property, string $oldClass, string $newClass): void
    {
        if ($property->type !== null) {
            // fix later
            throw new NotImplementedYetException();
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($property);

        $oldToNewTypes = [
            new OldToNewType(new FullyQualifiedObjectType($oldClass), new FullyQualifiedObjectType($newClass)),
        ];
        $this->docBlockClassRenamer->renamePhpDocType($phpDocInfo, $oldToNewTypes);

        if ($phpDocInfo->hasChanged()) {
            // invoke phpdoc reprint
            $property->setAttribute(AttributeKey::ORIGINAL_NODE, null);
        }
    }
}
