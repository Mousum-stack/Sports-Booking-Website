function get_carousel() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/carousel_crud.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onload = function () {
    if (xhr.status == 200) {
      document.getElementById("carousel-data").innerHTML = this.responseText;
    } else {
      alert("Error: " + xhr.statusText);
    }
  };

  xhr.onerror = function () {
    alert("Error: " + xhr.statusText);
  };

  xhr.send("get_carousel=true");
}

function rem_image(val) {
  
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/carousel_crud.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
      if (this.responseText == 1) {
        alert('success','Image removed!');
        get_carousel();
      } else {
        alert('Error', 'Server down!');
      }
    };

    xhr.send('rem_image=' + val);
  }


window.onload = function () {
  get_carousel();
};
