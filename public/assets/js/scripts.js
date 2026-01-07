//Global variable to save the interval.
let slideInterval;

//Function to start the slide of images
function startSlide(element){
    //Save into variables the first image and the progress bar
    const imgElement = element.querySelector('.first-image');
    const progressBar = element.querySelector('.progress-bar');
    //Get the images from data attribute and parse it to an array. We parse it because data attributes are always strings
    let images = [];
    try{
        images = JSON.parse(element.getAttribute('data-images'));
    } catch(e){
        console.error("Error parseando atributo data-images: ", e);
        return;
    }

    //If there ar less of 2 or 1 images, do nothing
    if(!images || images.length <= 1) return;
    //Save the first image src using dataset property
    if(!element.dataset.originalSrc){
        element.dataset.originalSrc = imgElement.src;
    }
    //Initialize the index
    let currentIndex = 0;
    //Change to the first image in the array
    imgElement.src = images[currentIndex];

    //Auxiliar function to move the progress bar
    const runProgressBar = () => {
        progressBar.style.transition = 'none';
        progressBar.style.width = '0%';
        //Timeout to allow the width reset to 0 before starting the transition.
        setTimeout(() =>{
            progressBar.style.transition = 'width 3s linear';
            progressBar.style.width = '100%';
        }, 50);
    };
    //Start the progress bar animation
    runProgressBar();
    //Set the interval to change the image every 3 seconds
    slideInterval = setInterval(() =>{
        //Calc the next index
        currentIndex = (currentIndex + 1) % images.length;
        //Change the image src
        imgElement.src = images[currentIndex];
        //Restart the progress bar animation
        runProgressBar();
    }, 3000); //3seconds

    //save the interval id in the element dataset to stop it later
    element.dataset.intervalId = slideInterval;
}

//Function to stop the slide of images
function stopSlide(element){
    //Stop the interval using the id saved in dataset
    clearInterval(element.dataset.intervalId);
    //Reset the image to the original src
    const imgElement = element.querySelector('.first-image');
    if(element.dataset.originalSrc){
        imgElement.src = element.dataset.originalSrc;
    }
    //Reset the progress bar
    const progressBar = element.querySelector('.progress-bar');
    progressBar.style.transition = 'none';
    progressBar.style.width = '0%';


}