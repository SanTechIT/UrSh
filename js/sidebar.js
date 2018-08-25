/* Set the width of the side navigation to 250px and the left margin of the page content to 250px and add a black background color to body */
function openNav() {
    document.getElementById("sidenav").style.height = "250px";
    document.getElementById("content-main").style.marginTop = "250px";  document.getElementById("overlay").style.display="block";
}

/* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
function closeNav() {
    document.getElementById("sidenav").style.height = "0";
    document.getElementById("content-main").style.marginTop = "0";	document.getElementById("overlay").style.display="none";
}