
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Play Hard- Venues</title>
<?php require('inc/links.php'); ?>
</head>
<body class="bg-light">
   <?php require('inc/header.php');?>
   <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">Book Venues</h2>
    <div class="h-line bg-dark"></div>
   </div>

   <div class="container-fluid">
    <div class="row">
       <div class="col-lg-3 col-md-12 mb-lg-0 mb-4 ps-4">
        <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow">
            <div class="container-fluid flex-lg-column align-items-stretch">
              <h4 class="mt-2">FILTERS</h4>
              <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#filterDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse flex-column mt-2 align-items-stretch" id="filterDropdown">
                <div class="border bg-light p-3 rounded mb-3">
                    <h5 class="d-flex align-items-center justify-content-between mb-3" style="font-size: 18px;">
                    <span>Check Availability</span>
                    <button id="chk_avail_btn" onclick="chk_avail_clear()" class="btn shadow-none btn-sm text-secondary d-none">RESET</button>
                  </h5>
                    <label  class="form-label">Booking From</label>
                    <input type="date" class="form-control shadow-none mb-3" id="checkin" onchange="chk_avail_filter()"> 
                    <label  class="form-label">Booking To</label>
                    <input type="date" class="form-control shadow-none mb-3" id="checkout" onchange="chk_avail_filter()"> 
                </div>
                <div class="border bg-light p-3 rounded mb-3">
                    <h5 class="d-flex align-items-center justify-content-between mb-3" style="font-size: 18px;">
                        <span>Select Game</span>
                        <button id="facilities_btn" onclick="facilities_clear()" class="btn shadow-none btn-sm text-secondary d-none">RESET</button>
                    </h5>           
                    <?php
                      // Assuming the 'venues' table exists and 'selectAll' is a defined function
                      $facilities_q = selectAll('venues');
                      while($row = mysqli_fetch_assoc($facilities_q))
                      {
                        echo <<<facilities
                        <div class="mb-2">
                        <input type="checkbox" onclick="fetch_room()" name="facilities" value="{$row['id']}" id="{$row['id']}" class="form-check-input shadow-none me-1"> 
                        <label  class="form-check-label" for="{$row['id']}">{$row['name']}</label>
                        </div>
                      facilities;
                      }
                    ?>        
                </div>
              </div>
            </div>
          </nav>
       </div>
       <div class="col-lg-9 col-md-12 px-4" id="rooms-data">
          
       </div>
       
    </div>
   </div>

   <script>
    let rooms_data = document.getElementById('rooms-data');
    let checkin = document.getElementById('checkin');
    let checkout = document.getElementById('checkout');
    let chk_avail_btn = document.getElementById('chk_avail_btn');
    let facilities_btn = document.getElementById('facilities_btn');

    function fetch_room() {
      let chk_avail = JSON.stringify({
        checkin: checkin.value,
        checkout: checkout.value
      });

      let facility_list = {"facilities":[]};
      let get_facilities = document.querySelectorAll('[name="facilities"]:checked');
      
      if (get_facilities.length > 0) {
        get_facilities.forEach((facility) => {
          facility_list.facilities.push(facility.value);
        });
        facilities_btn.classList.remove('d-none');
      } else {
        facilities_btn.classList.add('d-none');
      }
      
      facility_list = JSON.stringify(facility_list);

      let xhr = new XMLHttpRequest();
      xhr.open("GET", `ajax/rooms.php?fetch_rooms&chk_avail=${chk_avail}&facility_list=${facility_list}`, true);

      xhr.onprogress = function() {
        rooms_data.innerHTML = `<div class="spinner-border text-info mb-3 d-block mx-auto" id="loader" role="status">
              <span class="visually-hidden">Loading...</span>
          </div>`;
      };

      xhr.onload = function() {
        rooms_data.innerHTML = this.responseText;
      };
      
      xhr.send();
    }

    function chk_avail_filter() {
      if (checkin.value != '' && checkout.value != '') {
        fetch_room();
        chk_avail_btn.classList.remove('d-none');
      }
    }

    function chk_avail_clear() {
      checkin.value = '';
      checkout.value = '';
      chk_avail_btn.classList.add('d-none');
      fetch_room();
    }

    function facilities_clear() {
      let get_facilities = document.querySelectorAll('[name="facilities"]:checked');
      get_facilities.forEach((facility) => {
      facility.checked=false;
      });
      facilities_btn.classList.add('d-none');
      fetch_room();
    }



    fetch_room();
   </script>
    
    <?php require('inc/footer.php'); ?>
</body>
</html>
