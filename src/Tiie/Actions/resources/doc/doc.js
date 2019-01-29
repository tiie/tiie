(function(scope){
    class Doc {
        constructor() {
            this.p = {};
        }

        data(data) {
            this.p.data = data;

            return this;
        }

        run() {
            console.debug('run', this.p.data);
        }
    }

    scope.Doc = Doc;

})(window);
