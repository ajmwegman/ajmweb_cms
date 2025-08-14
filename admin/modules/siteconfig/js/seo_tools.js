$(document).ready(function () {
  function updateSnippet() {
    var title = $("#snippet-title").val() || "Voorbeeld titel";
    var description = $("#snippet-description").val() || "Voorbeeld beschrijving";
    $("#preview-title").text(title);
    $("#preview-description").text(description);
  }
  $("#snippet-title, #snippet-description").on("input", updateSnippet);

  $("#analyze-keywords").on("click", function () {
    var text = $("#keyword-content").val().toLowerCase();
    var words = text.match(/\b(\w+)\b/g) || [];
    var counts = {};
    words.forEach(function (w) {
      if (w.length <= 3) return;
      counts[w] = (counts[w] || 0) + 1;
    });
    var sorted = Object.entries(counts).sort(function (a, b) { return b[1] - a[1]; }).slice(0, 10);
    var list = $("#keyword-results").empty();
    sorted.forEach(function (pair) {
      list.append($("<li>").text(pair[0] + " (" + pair[1] + ")"));
    });
  });

  $("#check-links").on("click", function () {
    var urls = $("#link-checker-urls").val().split(/\n+/).filter(Boolean);
    var list = $("#link-results").empty();
    urls.forEach(function (url) {
      $.post("/admin/modules/siteconfig/tools/broken_link_checker.php", { url: url }, function (data) {
        list.append($("<li>").text(url + ": " + data.status));
      }, "json").fail(function () {
        list.append($("<li>").text(url + ": fout bij controleren"));
      });
    });
  });
});
