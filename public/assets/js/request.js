/**
 *  Request Backend Class 
 *          Make a request to the back end
 *  @param {String} req [default : ""]
 *  @param {String} method [default : "POST"]
 *  @param {Object} params [default : {}] 
 */
class RequestBackend{    
    constructor(req = "", method = "POST", params = null, type = 'json'){
        this.req = req;
        this.method = method;
        this.params = params;
        this.makeRequest = "";
        this.type = type;
    }

    /**
     *  Prepare
     *      Prepare the request to the back end 
     *  @chainable
     *  @return {this} Object 
     */
    prepare(){
        const headers = new Headers();
        // Precise that we want a JSON back to the front
        if (this.type === 'json'){
            console.log('json bitches');
            headers.append('Content-type', 'application/json');
        }
        
        // Init our API
        const config = {
            method: this.method,
            headers: headers,
            mode: 'cors',
            cache: 'default',
        }

        // Check if there're param in our request constructor ...
        if ((this.method === 'POST' || this.method === 'PUT') && this.type === 'json'){
            config.body = JSON.stringify(this.params);
        }
        else {
            config.body = this.params;
        }
            
        console.log('http://www.douceurs-coree.dev' + this.req);
        // Prepare the request
        this.makeRequest = new Request('http://www.douceurs-coree.dev' + this.req, config);

        return this;
    }

    /**
     *  Execute
     *          Execute the request
     *  @return {Promise} promise
     */
    execute(){
        const innerReq = this.makeRequest;
        let promise = new Promise(function(resolve, reject) {
            fetch(innerReq)
                .then((response) => {
                    response.json()
                        .then(json => {
                            resolve(json);
                        })
                        .catch(error => {
                            reject(error);
                        })
                })
                .catch((error) => {
                    reject(error);
                });
        });

        return promise;
    }
}
