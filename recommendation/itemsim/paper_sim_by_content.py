import MySQLdb
import sys
sys.path.append("../../include/python/")
from paper import Paper
import math
from operator import itemgetter

stopwords = set(['am', 'is', 'are', 'who', 'this', 'that', 'what', 'where'])

for y in range(0, 30):
    year = 2012 - y
    print year
    connection1 = MySQLdb.connect(host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
    cursor1 = connection1.cursor()
    connection2 = MySQLdb.connect(host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
    cursor2 = connection1.cursor()
    cursor2.execute("truncate table tmp_paper_entities;")
    cursor1.execute("select id, title, booktitle,journal,year from paper where year=%s", (year))
    entity_dict = dict()
    numrows = int(cursor1.rowcount)
    print numrows
    for k in range(numrows):
        if k % 10000 == 0:
            print k
        row = cursor1.fetchone()
        paper_id = row[0]
        entities = dict()
        words = row[1].lower().split()
        booktitle = row[2]
        journal = row[3]
        entities["b:" + booktitle] = 1
        entities["j:" + journal] = 1
        for word in words:
            if word in stopwords:
                continue
            if word not in entities:
                entities[word] = 1
            else:
                entities[word] = entities[word] + 1

        for i in range(0, len(words) - 1):
            word = words[i] + '_' + words[i + 1]
            if word not in entities:
                entities[word] = 1
            else:
                entities[word] = entities[word] + 1
        
        for (entity,weight) in entities.items():
            entity_id = len(entity_dict)
            if entity in entity_dict:
                entity_id = entity_dict[entity]
            else:
                entity_dict[entity] = entity_id
            cursor2.execute("replace into tmp_paper_entities (paper_id, entity_id, weight) values (%s, %s, %s)", (paper_id,entity_id,weight))
    cursor1.close()
    connection1.close()
    cursor2.close()
    connection2.close()
    
    connection = MySQLdb.connect (host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
    cursor = connection.cursor()

    simTable = dict()
    cursor.execute("select paper_id,entity_id from tmp_paper_entities order by entity_id;")

    numrows = int(cursor.rowcount)
    print numrows

    prev_entity = -1
    papers = []
    for k in range(numrows):
        if k % 10000 == 0:
            print k
        row = cursor.fetchone()
        entity_id = row[1]
        paper_id = row[0]
        if prev_entity != entity_id:
            if len(papers) < 200:
                for i in papers:
                    if i not in simTable:
                        simTable[i] = dict()
                    for j in papers:
                        if i == j:
                            continue
                        if j not in simTable[i]:
                            simTable[i][j] = 0
                        weight = 1 / math.log(2 + len(papers))
                        simTable[i][j] = simTable[i][j] + weight
            prev_entity = entity_id
            papers = []
        papers.append(paper_id)
    print len(simTable)

    if y==0:
        cursor.execute("truncate table papersim_content;")
    n = 0
    for i, rels in simTable.items():
        n = n + 1
        if n % 10000 == 0:
            print n
        k = 0
        for j, weight in sorted(rels.items(), key=itemgetter(1), reverse=True):
            cursor.execute("replace into papersim_content(src_id,dst_id,weight) values (%s,%s,%s);",(i, j, weight))
            k = k + 1
            if k > 10:
                break

    connection.commit()
    cursor.close()
    connection.close()
