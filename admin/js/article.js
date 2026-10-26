function searchArticle(){

    let input = document
        .getElementById("searchArticle")
        .value
        .toLowerCase();

    let rows = document.querySelectorAll("#articleTable tbody tr");

    rows.forEach(function(row){

        row.style.display =
        row.innerText.toLowerCase().includes(input)
        ? ""
        : "none";

    });

}

function openArticleModal(){

    document.getElementById("articleForm").reset();

    document.getElementById("articleModalTitle").innerHTML =
    "Add Article";

    document.getElementById("saveArticleBtn").innerHTML =
    "Save Article";

    document.getElementById("saveArticleBtn").name =
    "add_article";

    document.getElementById("article_id").value = "";

    document.getElementById("articleModal").style.display = "flex";

}

function closeArticleModal(){

    document.getElementById("articleModal").style.display = "none";

}

function editArticle(id, article_name, category, description){

    document.getElementById("articleModalTitle").innerHTML =
    "Edit Article";

    document.getElementById("saveArticleBtn").innerHTML =
    "Update Article";

    document.getElementById("saveArticleBtn").name =
    "update_article";

    document.getElementById("article_id").value = id;

    document.getElementById("article_name").value = article_name;

    document.getElementById("category").value = category;

    document.getElementById("description").value = description;

    document.getElementById("articleModal").style.display = "flex";

}

function deleteArticle(id){

    if(confirm("Delete this article?")){

        window.location.href =
        "dashboard.php?page=article&delete_article=" + id;

    }

}
