<?php

// コマンドライン引数からJSONファイルを読み込む
$jsonFile = $argv[1];
$outputFile = "output.md";

// ファイル名が重複している場合には、末尾の数字を増やしていく
$counter = 1;
while (file_exists($outputFile)) {
    // ファイル名の末尾に数字を追加
    $outputFile = "output_" . $counter . ".md";
    $counter++;
}

// ファイルを読み込んでデコード
$jsonData = json_decode(file_get_contents($jsonFile), true);

// 出力用の配列を準備
$markupList = ["```mermaid\n\nclassDiagram". "\n\n"];

// データを解析してmarkupListに追加
foreach ($jsonData['records'] as $member) {
    $compName = $member['MetadataComponentName'];
    $refName = $member['RefMetadataComponentName'];
    $markupList[] = "    class ". $compName."{\n\n    }"."\n".
                    "    class ". $refName."{\n\n    }"."\n"
                    ."    ".$compName . " --> " . $refName . " : " . "\n\n";
}

// 出力用の配列を準備
$markupList[] = "\n```";

// ファイルに書き込み
file_put_contents($outputFile, implode('', $markupList));

/* 以下API参考_{Instance_URL}は組織のインスタンス名*/
/*
{Instance_URL}/services/data/v47.0/tooling/query?q=SELECT+MetadataComponentId,+MetadataComponentName,+ MetadataComponentNamespace,+MetadataComponentType,+RefMetadataComponentId,+ RefMetadataComponentName,+RefMetadataComponentNamespace,+RefMetadataComponentType+
FROM+MetadataComponentDependency+
WHERE+RefMetadataComponentType+=+'ApexClass'
*/

// 'php change.php {{該当のJsonファイル}}'にて実行

?>
