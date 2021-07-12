const languageSelector = lang => {
    let language = lang;

    fetch(`http://skills-it.hu/assets/lang/${language}.json`)
    .then(response => response.json())
    .then(response => {
        document.getElementById("lang1").innerText = response["select-accomodation"];
        document.getElementById("lang2").innerText = response["check-in"];
        document.getElementById("lang3").innerText = response["check-out"];
        document.getElementById("lang4").innerText = response["adults"];
    });
};

const loadAccomodations = ( fav ) => {
    fetch('http://skills-it.hu/api/accomodations')
    .then(response => response.json())
    .then(response => {
        let list = document.getElementById("main-content");
        list.innerHTML = "";

        if ( fav == undefined || fav == false )
        {
            response.forEach(element => {
                list.innerHTML += '<div class="card">'+
                                    '<div class="title-row">' +
                                        '<p class="title">'+element.name+'</p>' +
                                        '<svg data-id="'+element.id+'" onclick="addFavourite(this)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M528.1 171.5L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6zM388.6 312.3l23.7 138.4L288 385.4l-124.3 65.3 23.7-138.4-100.6-98 139-20.2 62.2-126 62.2 126 139 20.2-100.6 98z"/></svg>'+
                                        '</div>' +
                                    '<img src="assets/img/'+element.img+'" alt="">' +
                                '<p class="description">'+element.description+'</p>' +
                            '</div>';
            });
        }
        else
        {
            response.forEach(element => {
                let obj = JSON.parse(localStorage.favourites);

                if ( obj.includes( ""+element.id+"" ) )
                {
                    list.innerHTML += '<div class="card">'+
                                    '<div class="title-row">' +
                                        '<p class="title">'+element.name+'</p>' +
                                        '<svg data-id="'+element.id+'" onclick="addFavourite(this)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M528.1 171.5L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6zM388.6 312.3l23.7 138.4L288 385.4l-124.3 65.3 23.7-138.4-100.6-98 139-20.2 62.2-126 62.2 126 139 20.2-100.6 98z"/></svg>'+
                                        '</div>' +
                                    '<img src="assets/img/'+element.img+'" alt="">' +
                                '<p class="description">'+element.description+'</p>' +
                            '</div>';
                }
            });

            if ( list.innerHTML == "" ) list.innerHTML = "There is nothing.";
        }
    });
}

const addFavourite = event => {
    
    let obj = JSON.parse(localStorage.favourites);
    if ( obj.includes( ""+event.dataset.id ) )
    {
        obj.splice( obj.indexOf(""+event.dataset.id), 1 );
        localStorage.favourites = JSON.stringify(obj);
    }
    else
    {
        obj.push( event.dataset.id );
        localStorage.favourites = JSON.stringify(obj);
    }

};

const dropdown = () => {
    document.getElementById("dropdownMenu").classList.toggle('active');
};

const dropdownSelect = (event) => {
    event.parentNode.parentNode.childNodes[1].innerText = event.innerText;
    event.parentNode.parentNode.childNodes[1].dataset.id = event.dataset.id;
    document.getElementById("dropdownMenu").classList.toggle('active');
};

if ( localStorage.favourites == "" || localStorage.favourites == undefined )
{
    localStorage.favourites = JSON.stringify([]);
}

loadAccomodations();

// Load dropdown
document.getElementById("dropdownMenu").innerHTML = "";
fetch('http://skills-it.hu/api/accomodations')
    .then(response => response.json())
    .then(response => {
        response.forEach(element => {
            document.getElementById("dropdownMenu").innerHTML += '<a style="cursor: pointer" data-id="'+element.id+'" onclick="dropdownSelect(this)" class="dropdown-item">'+element.name+'</a>';
        });
    });

document.getElementById("adults").addEventListener("change", (event) => {
    let checkin = document.getElementById("checkin").value;
    let checkout = document.getElementById("checkout").value;
    let acc = document.getElementById("lang1").dataset.id;
    let adults = document.getElementById("adults").value

    if ( checkin.value !== "" && checkout.value !== "" && acc !== "null" )
    {
        document.getElementById("main-content").classList.add("invisible");
        document.getElementById("calendar").classList.add("invisible");
        document.getElementById("favourite").classList.add("invisible");
        document.getElementById("send").classList.remove("invisible");

        fetch("http://skills-it.hu/api/accomodations")
        .then(response => response.json())
        .then(response => {
            response.forEach(element => {
                if (element.id == acc)
                {
                    document.getElementById("adult-counter").innerText = adults;
                    document.getElementById("night-counter").innerText = ((new Date(checkout)).getTime() - (new Date(checkin)).getTime()) / (3600*1000)/24;
                    document.getElementById("price-c").innerText = (((new Date(checkout)).getTime() - (new Date(checkin)).getTime()) / (3600*1000)/24) * adults * element.price;
                }
            });
        })
        .catch(error => {

        });
    }
}); 

document.getElementById("checkout").addEventListener("change", (event) => {
    let checkin = document.getElementById("checkin").value;
    let acc = document.getElementById("lang1").dataset.id;

    if ( checkin.value !== "" && acc !== "null" )
    {
        document.getElementById("main-content").classList.toggle("invisible");
        document.getElementById("calendar").classList.toggle("invisible");
        document.getElementById("favourite").classList.toggle("invisible");
    }
});

const sendBooking = () => {
    document.getElementById("send").innerHTML = '<h1>Send booking</h1><p>Thanks for booking.</p>';
};