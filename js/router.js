var Router = function() {

    var self = this,
        routes = [],
        redirects = [];

    /**
     * Add a set of routes and their callbacks to the Router
     * Params: alternating RegExp objects and functions are expected
     */
    this.routes = function() {
        if (arguments.length % 2 !== 0) {
            throw new Error("Even number of arguments required");
        }
        for (var i=0, len=arguments.length; i<len; i+=2) {
            if (!(arguments[i] instanceof RegExp)) {
                throw new Error("Expected a RegExp expression in odd position");
            }
            if (typeof arguments[i+1] !== "function") {
                throw new Error("Expected a function in even position");
            }
            routes.push([arguments[i], arguments[i+1]]);
        }
    };
    
    /**
     * Add a set of routes and their redirect targets to the Router
     * Params: alternating RegExp objects and strings are expected
     */
    this.redirects = function() {
        if (arguments.length % 2 !== 0) {
            throw new Error("Even number of arguments required");
        }
        for (var i=0, len=arguments.length; i<len; i+=2) {
            if (!(arguments[i] instanceof RegExp)) {
                throw new Error("Expected a RegExp expression in odd position");
            }
            if (typeof arguments[i+1] !== "string") {
                throw new Error("Expected url as string in even position");
            }
            redirects.push([arguments[i], arguments[i+1]]);
        }
    };

    /**
     * Navigate to a specific URL (hash, in fact)
     */
    this.navigate = function(url, triggerEvents) {
        triggerEvents = triggerEvents || (triggerEvents === undefined);
        if (!triggerEvents) window.removeEventListener("hashchange", urlUpdated);
        window.location.hash = url;
        /* ugly hack to stack events */
        if (!triggerEvents) setTimeout(function() {
            window.addEventListener("hashchange", urlUpdated);
        }, 0);
    };

    /**
     * Check if an URL matches routes or redirects
     */
    function dispatch(url) {
        /* First check routes */
        var i, len, exp, func, vals;
        for (i=0, len=routes.length; i<len; i++) {
            exp = routes[i][0];
            func = routes[i][1];
            vals = url.match(exp);
                
            if (vals) {
                vals.shift();
                func.apply(window, vals);
                return;
            }
        }
        /* Check redirects */
        var target;
        for (i=0, len=redirects.length; i<len; i++) {
            exp = redirects[i][0];
            target = redirects[i][1];
                
            if (exp.test(url)) {
                self.navigate(target);
                return;
            }
        }
        throw new Error("No matching routes or redirects!");
    }
    
    /**
     * Convenience slot to call on "load" and "hashchange" events
     */
    function urlUpdated() {
        dispatch(window.location.hash);
    }

    /* Initialize */
    window.addEventListener("load", urlUpdated);
    window.addEventListener("hashchange", urlUpdated);

};
