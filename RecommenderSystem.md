# Introduction #

Main Function of Recommender System

```
function makingRecommendation($uid, $relatedTables)
{
        $recommendations = array();
        $behaviors = GetBehavior($uid);
        $features = $behaviors;
        
        foreach($relatedTables as $table_name => $table_weight)
        {
                combineArray($recommendations, recommendationCore($features, $table_name, 10), $table_weight);
        }
        
        selectExplanation($recommendations);
        filtering($recommendations);
        ranking($recommendations);
        
}
```


# Details #

Add your content here.  Format your content with:
  * Text in **bold** or _italic_
  * Headings, paragraphs, and lists
  * Automatic links to other wiki pages