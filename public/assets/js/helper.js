/**
 *  Helper 
 *          A list of method of helper that can be used threw the project.
 */
const helper = {
    /**
     *  Add listener 
     *          Add a listener to an object and bind if wishes the helper it self
     *  @param {String} DOMString 
     *  @param {String} type
     *  @param {Function} callback
     *  @param {Boolean} bindObject [default = false]
     */
    addListener(DOMString, type, callback, bindObject = false){
        if(bindObject){
            callback = callback.bind(this);
        }
        
        if (type === 'class'){
            let elements = document.getElementsByClassName(DOMString);
            for (let element of elements){
                element.addEventListener('click', callback);
            }
        } else {
            document.getElementById(DOMString).addEventListener('click', callback);
        }
    }
};