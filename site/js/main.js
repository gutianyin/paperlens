function recommend(uid, paper, behavior, w, node_id)
{
	node_body = document.getElementsByTagName("body")[0];
	node_body.innerHTML += '<img width=0 height=0 src=/site/behavior.php?uid=' + uid + '&paper=' + paper + '&behavior=' + behavior + '&w=' + w + ' />';
	
	node = document.getElementById(node_id);
	node.innerHTML = 'thanks!';
}

function google_search(uid, paper, behavior, w, node_id)
{
	node_body = document.getElementsByTagName("body")[0];
	node_body.innerHTML += '<img width=0 height=0 src=/site/behavior.php?uid=' + uid + '&paper=' + paper + '&behavior=' + behavior + '&w=' + w + ' />';
}

function showMore()
{
	node_show_more = document.getElementById("show_more");
	alert(node_show_more.style.display);
	node_show_more.style.display = "none;";
}