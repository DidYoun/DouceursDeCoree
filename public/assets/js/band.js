/**
 *  Band Helper 
 *          A method that control the band create view
 *  @public
 */
const band = (function () {
    let listOfDouceur = {
        group_name: 'foo',
        list: []
    };

    let douceursUpdate = Object.assign(Object.create({}), {
        delete: [],
        new: []
    });

    let newCoverValue = false;

    // Create a reference to our Helper
    const helperFunc = Object.create(helper);

    /**
     *  Alert 
     *          Show an alert to the user and close it ..
     */
    const alert = (err, succ) => {
        let parent = document.getElementById('container');
        if (err)
            parent.insertAdjacentHTML('beforeend', `<div class="alert alert-danger" id='alert-douceur' role="alert">${err}</div>`);
        else
            parent.insertAdjacentHTML('beforeend', `<div class="alert alert-success" role="alert" id='alert-douceur'>Congratulations ${succ}</div>`);

        // remove the alert after 2sec
        setTimeout(() => {
            document.getElementById('alert-douceur').remove();
        }, 2000);
    };


    /**
     *  Add Douceur
     *          add douceur to a group
     */
    const addDouceur = function (state = {}) {
        console.log(this);
        let attr = this.getAttribute('data-id');

        if (listOfDouceur.list.indexOf(attr) != -1) {
            alert('vous avez déja selectionner cette douceur');
            return;
        }


        listOfDouceur.list.push(this.getAttribute('data-id'));

        if (state.status === 'update') {
            douceursUpdate.new.push(this.getAttribute('data-id'));
            alert(`You've just add ${this.getAttribute('data-name')}`)
        }

        this.classList.remove('btn-info');
        this.classList.add('btn-default');
        this.nextElementSibling.classList.add('btn-info');
        this.nextElementSibling.classList.remove('btn-default');
    };

    /**
     *  Remove Douceur 
     *          Remove a douceur from the list of doucer
     *  @param {string} 
     */
    const removeDouceur = function () {
        let index = listOfDouceur.list.indexOf(this.getAttribute('data-id'));

        if (index !== -1) {
            listOfDouceur.list.splice(index, 1);
            //console.log(this.previousSibling);
            this.previousElementSibling.classList.remove('btn-default');
            this.previousElementSibling.classList.add('btn-info');
            this.classList.remove('btn-info');
            this.classList.add('btn-default');
        }
    };

    /**
     *  Create Group Douceur
     *          Create a group of douceur based on the params..
     */
    const prepareGroupDouceur = () => {
        let name = document.getElementById('name');
        let date = document.getElementById('date');
        let agency = document.getElementById('agency');
        let imgFile = document.getElementById('fileInput');
        let desc = document.getElementById('group');

        if (imgFile.value)
            imgFile = imgFile.files[0];
        else
            imgFile = imgFile.value;

        let fd = new FormData();

        helperFunc.FilesHelper(imgFile)
            .then(res => {
                listOfDouceur.name = helperFunc.empty(name.value, 'name', name);
                listOfDouceur.agency = helperFunc.empty(agency.value, 'agency', agency);
                listOfDouceur.date = helperFunc.empty(date.value, 'date', date);
                listOfDouceur.membersLength = listOfDouceur.list.length;
                listOfDouceur.description = helperFunc.empty(desc.value, 'description', desc);
                fd.append('datas', JSON.stringify(listOfDouceur));
                fd.append('file', imgFile);

                if (listOfDouceur.list.length === 0)
                    return Promise.reject('you must add a douceur');
            })
            .then(createGroupDouceur.bind(null, fd))
            .catch(err => {
                alert(err);
            });
    };

    /**
     *  Create Group Douceur
     *          Create a group of douceur
     *  @param {Object} params
     */
    const createGroupDouceur = (params = {}) => {
        // make a request toward the back end

        let req = new RequestBackend('/band/new', 'POST', params, 'form-data');
        req.prepare().execute()
            .then(res => {
                if (res.url)
                    window.location.href = '/band';
            })
            .catch(err => {
                console.log(err);
                return Promise.reject('err');
            });
    }

    /**
     *  Delete Band 
     *          Delete a band from the DB
     */
    const deleteBand = function () {
        // first remove every member of the band 
        removeAllBand().then(res => {
                let id = this.getAttribute('data-id');
                const req = new RequestBackend(`/band/${id}`, 'DELETE');
                req.prepare().execute()
                    .then(res => {
                        console.log(res);
                        if (res.url)
                            window.location.href = res.url;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            })
            .catch(err => {
                alert(err)
            });
        // then remove the band 
    }

    /**
     *  Remove Artist in Band 
     *       remove an artist of a band    
     */
    const removeBand = function () {
        let douceurLength = document.getElementsByClassName('douceur-rm').length;

        if (douceurLength > 1) {
            let band = document.getElementById(`douceur_${this.getAttribute('data-id')}`);
            douceursUpdate.delete.push(`douceur_${this.getAttribute('data-id')}`);
            band.remove();
        } else {
            // provided a feedback to the user 
        }
    };

    const removeAllBand = () => {
        let douceurs = document.getElementsByClassName('douceur-rm');
        for (let douceur of douceurs) {
            douceursUpdate.delete.push(douceur.id);
        }

        return new Promise((resolve, reject) => {

            if (douceursUpdate.delete.length === 0)
                resolve(true);

            const req = new RequestBackend('/band/removeAll', 'POST', douceursUpdate);

            req.prepare().execute()
                .then(res => {
                    resolve(true);
                })
                .catch(err => {
                    reject(err);
                })
        });
    };

    /**
     * Save State Date 
     */
    const saveStateDate = () => {
        return currentDate = document.getElementById('date').value;
    };

    /**
     * Update Band 
     *      Update band 
     * @private
     */
    const updateBand = function (currentDate) {
        let fd = new FormData();
        let data = {};
        // get the id of the band 
        let id = document.getElementById('container').getAttribute('band-id');

        // retrieve the data from the input.. 
        let inputData = document.getElementsByClassName('form-control');

        for (let i of inputData) {
            // If there's update data push threw the Object 
            if (i.value && i.getAttribute('param') !== 'file')
                if (i.getAttribute('param') !== 'date')
                    data[i.getAttribute('param')] = i.value.trim();
                else
                    // check if the date has changed
                    if (i.value !== currentDate)
                        data[i.getAttribute('param')] = i.value.trim();

            if (i.getAttribute('param') === 'file' && newCoverValue)
                fd.append('file', newCoverValue);
        }

        // if there's no change we does not update the band
        if (douceursUpdate.delete.length != 0 || douceursUpdate.new.length != 0)
            data.band = douceursUpdate;


        if (Object.keys(data).length === 0 && !newCoverValue)
            return;
        // add the id to the datas to send 
        data.id = id;
        fd.append('datas', JSON.stringify(data));
        const req = new RequestBackend(`/band/update`, 'POST', fd, 'form-data');
        req.prepare().execute()
            .then(res => {
                if (res.url)
                    window.location.href = res.url;
            })
            .catch(err => {
                alert(err);
            });
    }

    const updateImg = function () {
        helper.FilesHelper(this.files[0])
            .then(res => {
                document.getElementById('updateImg').src = res;
                newCoverValue = this.files[0];
            })
            .catch(err => {
                alert(err);
            });
    };

    /**
     *  Bootloader 
     *          Init the band page by adding the listener depending of the page ID
     *  @public
     */
    this.bootloader = () => {
        let pageID = document.getElementById('container').getAttribute('page-id');

        switch (pageID) {
            case 'band':
                //helperFunc.addListener('delete', 'class', deleteBand);
                break;
            case 'band-create':
                helperFunc.addListener('select', 'class', addDouceur);
                helperFunc.addListener('unselect', 'class', removeDouceur);
                helperFunc.addListener('create-group', 'id', prepareGroupDouceur, true);
                break;
            case 'band-edit':
                let date = saveStateDate();
                helperFunc.addListener('removeGroup', 'id', deleteBand);
                helperFunc.addListener('add', 'id', function () {
                    $('#myModal').modal('toggle');
                });
                helperFunc.addListener('select', 'class', addDouceur, null, 'click', {
                    status: 'update'
                });
                helper.addListener('delete', 'class', removeBand);
                helperFunc.addListener('update', 'id', updateBand.bind(null, date));
                helperFunc.addListener('file', 'id', updateImg, null, 'change');
                break;
        }
    };


    document.addEventListener('DOMContentLoaded', this.bootloader);
}.bind({}))();