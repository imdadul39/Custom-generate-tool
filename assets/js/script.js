function convertToSeconds(min, sec) {
    min = min ? parseInt(min, 10) : 0;
    sec = sec ? parseInt(sec, 10) : 0;
    return (min * 60) + sec;
}

function generateEmbedLink() {
    var url = document.getElementById("yt_video_url").value;
    var startMinutes = document.getElementById("start_minutes").value;
    var startSeconds = document.getElementById("start_seconds").value;
    var endMinutes = document.getElementById("end_minutes").value;
    var endSeconds = document.getElementById("end_seconds").value;

    if (!url.includes("youtube.com/watch?v=")) {
        alert("Please provide a valid YouTube link!");
        return;
    }

    var videoId = url.split("v=")[1].split("&")[0];
    var startTime = convertToSeconds(startMinutes, startSeconds);
    var endTime = convertToSeconds(endMinutes, endSeconds);

    var embedUrl = `https://www.youtube.com/embed/${videoId}?start=${startTime}&autoplay=1`;

    if (endTime > 0) {
        embedUrl += `&end=${endTime}`;
    }

    document.getElementById("generated_embed_link").value = embedUrl;
    document.getElementById("yt_embed").src = embedUrl;
    document.getElementById("yt_embed").style.display = "block";
    document.getElementById("copy_message").style.display = "none";
}

function copyEmbedLink() {
    var linkField = document.getElementById("generated_embed_link");
    linkField.select();
    document.execCommand("copy");
    document.getElementById("copy_message").style.display = "block";
}
