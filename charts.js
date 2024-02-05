let completedRequests;
let completedOffers;
let publishedRequests;
let publishedOffers;


// Load published requests data
let xhr1 = new XMLHttpRequest();
xhr1.open('GET', 'includes/noncomplete-requests-charts.inc.php', true);
xhr1.onload = function () {
    if (xhr1.status == 200) {
        // Get response as JSON data
        let nonCompleteRequestsData = JSON.parse(this.responseText);
        console.log('Published Requests Data:', nonCompleteRequestsData);

        const result = {};

        nonCompleteRequestsData.forEach(entry => {
            const { publish_date, requests } = entry;

            if (result[publish_date]) {
                result[publish_date] += requests;
            } else {
                result[publish_date] = requests;
            }
        });

        const publishedRequests = Object.keys(result).map(date => ({ publish_date: date, requests: result[date] }));

        console.log('Sorted Published Requests', publishedRequests);

        // Draw chart for published requests
        // drawChart('myChart', 'Published Requests', publishedRequests);

    } else {
        // Handle the error
        console.error('Error fetching JSON data. Status code: ' + xhr1.status);
    }
};
xhr1.send();



// Load published offers data
let xhr2 = new XMLHttpRequest();
xhr2.open('GET', 'includes/noncomplete-offers-charts.inc.php', true);
xhr2.onload = function () {
    if (xhr2.status == 200) {
        // Get response as JSON data
        let nonCompleteOffersData = JSON.parse(this.responseText);
        console.log('Published Offers Data:', nonCompleteOffersData);

        const result = {};

        nonCompleteOffersData.forEach(entry => {
            const { publish_date, offers } = entry;

            if (result[publish_date]) {
                result[publish_date] += offers;
            } else {
                result[publish_date] = offers;
            }
        });

        const publishedOffers = Object.keys(result).map(date => ({ publish_date: date, offers: result[date] }));

        console.log('Sorted Published Offers', publishedOffers);

    } else {
        // Handle the error
        console.error('Error fetching JSON data. Status code: ' + xhr2.status);
    }
};
xhr2.send();

// Load completed requests data
let xhr3 = new XMLHttpRequest();
xhr3.open('GET', 'includes/complete-requests-charts.inc.php', true);
xhr3.onload = function () {
    if (xhr3.status == 200) {
        // Get response as JSON data
        let completeRequestsData = JSON.parse(this.responseText);
        console.log('Complete Requests Data:', completeRequestsData);

        const result = {};

        completeRequestsData.forEach(entry => {
            const { complete_date, completed_requests } = entry;

            if (result[complete_date]) {
                result[complete_date] += completed_requests;
            } else {
                result[complete_date] = completed_requests;
            }
        });

        const completedRequests = Object.keys(result).map(date => ({ complete_date: date, completed_requests: result[date] }));

        console.log('Sorted Completed Requests', completedRequests);

    } else {
        // Handle the error
        console.error('Error fetching JSON data. Status code: ' + xhr3.status);
    }
};
xhr3.send();

// Load completed requests data
let xhr4 = new XMLHttpRequest();
xhr4.open('GET', 'includes/complete-offers-charts.inc.php', true);
xhr4.onload = function () {
    if (xhr4.status == 200) {
        // Get response as JSON data
        let completeOffersData = JSON.parse(this.responseText);
        console.log('Complete Offers Data:', completeOffersData);

        const result = {};

        completeOffersData.forEach(entry => {
            const { complete_date, completed_offers } = entry;

            if (result[complete_date]) {
                result[complete_date] += completed_offers;
            } else {
                result[complete_date] = completed_offers;
            }
        });

        const completedOffers = Object.keys(result).map(date => ({ complete_date: date, completed_offers: result[date] }));

        console.log('Sorted Completed Offers', completedOffers);

    } else {
        // Handle the error
        console.error('Error fetching JSON data. Status code: ' + xhr4.status);
    }
};
xhr4.send();

// Combine all the dates and the values in a single array of objects
const combinedObject = [
  ...publishedRequests,
  ...publishedOffers.map(item => ({ date: item.complete_date, requests: 0, offers: 0, completed_requests: item.completed_requests, completed_offers: 0 })),
  ...completedRequests,
  ...completedOffers.map(item => ({ date: item.complete_date, requests: 0, offers: 0, completed_requests: 0, completed_offers: item.completed_offers }))
];

console.log(combinedObject);

// Group the combinedObject by date
const groupedObject = combinedObject.reduce((acc, item) => {
  const date = item.date || item.publish_date;
  const existingItem = acc.find(entry => entry.date === date);

  if (existingItem) {
    existingItem.values.requests += item.requests || 0;
    existingItem.values.offers += item.offers || 0;
    existingItem.values.completed_requests += item.completed_requests || 0;
    existingItem.values.completed_offers += item.completed_offers || 0;
  } else {
    const newItem = {
      date,
      values: {
        requests: item.requests || 0,
        offers: item.offers || 0,
        completed_requests: item.completed_requests || 0,
        completed_offers: item.completed_offers || 0
      }
    };
    acc.push(newItem);
  }

  return acc;
}, []);

console.log(groupedObject);


function drawChart(){
  let ctx = document.getElementById('myChart').getContext('2d');
  let chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {
      labels: ['January'],
      datasets: [{
        label: 'My First dataset',
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgb(255, 99, 132)',
        data: [0, 10, 5, 2, 20, 30, 45]
      }]
    },


  })
};






