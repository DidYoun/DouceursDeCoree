const creationError = {
    name : 'Please enter a name of a group',
    date : 'Please enter a date',
    agency : 'Please enter an agency',
    desc : 'Please enter a description of the group'
}
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
    addListener(DOMString, type, callback, bindObject = false, listenerType = 'click', bindParam = {}){
        if(bindObject){
            callback = callback.bind(bindObject, bindParam);
        }
        
        if (type === 'class'){
            let elements = document.getElementsByClassName(DOMString);
            for (let element of elements){
                element.addEventListener(listenerType, callback.bind(element, bindParam));
            }
        } else {
            let element = document.getElementById(DOMString);
            document.getElementById(DOMString).addEventListener(listenerType, callback.bind(element, bindParam));
        }
    },
    /**
     *  Files Helper
     *          Check if a file is a type an image
     *  @param {Files} file
     *  @return {String} file Base64 value
     */
     FilesHelper(file){
        return new Promise((resolve, reject) => {
            if (file === undefined || file === null)
                reject('the file is not present');

            if (!file.type.match('image.*'))
                reject('the file is not an image');
            
            let reader  = new FileReader();

            if (file) {
                reader.readAsDataURL(file);
            }

            reader.addEventListener("load", function () {
                resolve(reader.result);
            }, false);
        });
    },
    /**
     * Empty 
     */
    empty(param, paramName, el = null){
        if (param === undefined || param === null || param.length === 0){
            if (el !== null)
                el.classList.add('has-error');

            throw `${paramName} is empty, ${creationError[paramName]}`;
        }
        
        return param;
    }
};