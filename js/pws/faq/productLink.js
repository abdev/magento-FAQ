    var productLinksController = Class.create();

    productLinksController.prototype = {
        initialize : function(fieldId, products, grid) {
            this.saveField = $(fieldId);
            this.saveFieldId = fieldId;
            this.products    = $H(products);
            this.grid        = grid;
            this.tabIndex    = 1000;
            this.grid.rowClickCallback = this.rowClick.bind(this);
            this.grid.initRowCallback = this.rowInit.bind(this);
            this.grid.checkboxCheckCallback = this.registerProduct.bind(this);
            this.grid.rows.each(this.eachRow.bind(this));
            this.saveField.value = this.serializeObject(this.products);
            this.grid.reloadParams = {'products[]':this.products.keys()};
        },
        eachRow : function(row) {
            this.rowInit(this.grid, row);
        },
        registerProduct : function(grid, element, checked) {
            if(checked){
                if(element.inputElements) {
                    this.products.set(element.value, {});
                    for(var i = 0; i < element.inputElements.length; i++) {
                        element.inputElements[i].disabled = false;
                        this.products.get(element.value)[element.inputElements[i].name] = element.inputElements[i].value;
                    }
                }
            }
            else{
                if(element.inputElements){
                    for(var i = 0; i < element.inputElements.length; i++) {
                        element.inputElements[i].disabled = true;
                    }
                }

                this.products.unset(element.value);
            }
            this.saveField.value = this.serializeObject(this.products);
            this.grid.reloadParams = {'products[]':this.products.keys()};
        },
        serializeObject : function(hash) {
            var clone = hash.clone();
            clone.each(function(pair) {
                clone.set(pair.key, encode_base64(Object.toQueryString(pair.value)));
            });
            return clone.toQueryString();
        },
        rowClick : function(grid, event) {
            var trElement = Event.findElement(event, 'tr');
            var isInput   = Event.element(event).tagName == 'INPUT';
            if(trElement){
                var checkbox = Element.select(trElement, 'input');
                if(checkbox[0]){
                    var checked = isInput ? checkbox[0].checked : !checkbox[0].checked;
                    this.grid.setCheckboxChecked(checkbox[0], checked);
                }
            }
        },
        inputChange : function(event) {
            var element = Event.element(event);
            if(element && element.checkboxElement && element.checkboxElement.checked){
                this.products.get(element.checkboxElement.value)[element.name] = element.value;
                this.saveField.value = this.serializeObject(this.products);
            }
        },
        rowInit : function(grid, row) {
            var checkbox = $(row).select('.checkbox')[0];
            var inputs = $(row).select('.input-text');
            if(checkbox && inputs.length > 0) {
                checkbox.inputElements = inputs;
                for(var i = 0; i < inputs.length; i++) {
                    inputs[i].checkboxElement = checkbox;
                    if(this.products.get(checkbox.value) && this.products.get(checkbox.value)[inputs[i].name]) {
                        inputs[i].value = this.products.get(checkbox.value)[inputs[i].name];
                    }
                    inputs[i].disabled = !checkbox.checked;
                    inputs[i].tabIndex = this.tabIndex++;
                    Event.observe(inputs[i],'keyup', this.inputChange.bind(this));
                    Event.observe(inputs[i],'change', this.inputChange.bind(this));
                }
            }
        }
    };
