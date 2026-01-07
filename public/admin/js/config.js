export const Config = {
    API_BASE_URL: 'http://primerproyectohni.com/'
};

//Function to build the full API url with endpoint and parameters
export function buildApiUrl(endpoint, params = {}) {
    //Create a url object with the base url
    const url = new URL(Config.API_BASE_URL);

    //Append the endpoint to the url
    url.searchParams.append('controller', 'api');
    url.searchParams.append('endpoint', endpoint);


    //Append the parameters to the url if is setted
    if (params && typeof params === 'object') {
        for (const key in params) {
            url.searchParams.append(key, params[key]);
        }
    }


    return url.toString();
}