const band = (function(){
    let listOfDouceur = {
        group_name : 'foo',
        list : []
    };

    const helperFunc = Object.create(helper);

    /**
     *  Add Douceur
     *          add douceur to a group
     */
    const addDouceur = function(){
        let attr = this.getAttribute('data-id');

        if(listOfDouceur.list.indexOf(attr) != -1)
            return;

        listOfDouceur.list.push(this.getAttribute('data-id'));
    };

    /**
     *  Remove Douceur 
     *          Remove a douceur from the list of doucer
     *  @param {string} 
     */
    const removeDouceur = function(){
        let index = listOfDouceur.list.indexOf(this.getAttribute('data-id'));
        listOfDouceur.list.splice(index, 1);
    };

    /**
     *  Create Group Douceur
     *          Create a group of douceur based on the params..
     */
    const createGroupDouceur = () => {
        let name = document.getElementById('name').value;
        let date = document.getElementById('date').value;
        let agency = document.getElementById('agency').value;

        listOfDouceur.name = name;
        listOfDouceur.agency = agency;
        listOfDouceur.date = date;
        listOfDouceur.membersLength = listOfDouceur.list.length();

        // make a request toward the back end

        let req = new RequestBackend('/');
    };
    
    /**
     *  Init
     *          Init the band page by adding the listener
     *  @private
     */
    this.init = () => {
        helperFunc.addListener('select', 'class', addDouceur);
        helperFunc.addListener('unselect', 'class', removeDouceur);
        helperFunc.addListener('create-group', 'id', createGroupDouceur, true);
    };

    document.addEventListener('DOMContentLoaded', this.init);
}.bind({}))();