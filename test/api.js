
const axios = require("axios");

const { BadRequestException } = require("./exception");

const JSEncrypt = require('node-jsencrypt');
const jsEncrypt = new JSEncrypt();

axios.defaults.baseURL = "http://192.168.146.3:9501"
axios.defaults.timeout = 500

axios.interceptors.response.use(
    res => res,
    err => {
        const res = err.response;
        if (res && res.data && res.data.code) {
            const debugData = (res.data.result && res.data.result.__debug) ? res.data.result.__debug : null;
            throw new BadRequestException(res.data.message, res.data.code, res.status, debugData);
        } else {
            throw new Error(JSON.stringify(res.data));
        }
    }
)

const api = {

    
    /**
    * Auth
    */
    auth: {
        login: async (type, subject, password) => {
            return await axios.post(`/v1/auth/login/${type}`, {subject, password});
        },
        signUpByUsername: async (username, password) => {
            return await axios.post(`/v1/auth/signup/username`, {username, password});
        },
        logout: async () => {
            return await axios.post(`/v1/auth/logout`);
        },
        getCaptcha: async (width = 180, height = 64, raw = true, uuid = "") => {
            return await axios.get(`/v1/auth/captcha`, {params: {width, height, raw, uuid}});
        },
        getPublicKey: async (raw = false) => {
            return await axios.get(`/v1/auth/pubkey`, {params: {raw}});
        },
    },
    
    /**
    * Index
    */
    index: {
        sum: async (a, b) => {
            return await axios.post(`/v1/sum`, {a, b});
        },
    },
}//api


api.auth.signUpByUsername = async (username, password, pubkey = null) => {
    jsEncrypt.setPublicKey(pubkey);
    const crendentials = jsEncrypt.encrypt(JSON.stringify({ username, password }))
    return await axios.post('/v1/auth/signup/username', { crendentials });
}

api.auth.login = async (type, subject, password, pubkey = null) => {
    jsEncrypt.setPublicKey(pubkey);
    const crendentials = jsEncrypt.encrypt(JSON.stringify({ subject, password }))
    return await axios.post(`/v1/auth/login/${type}`, { crendentials });
}

api.auth.loginByUsername = async (subject, password, pubkey = null) => {
    jsEncrypt.setPublicKey(pubkey);
    const crendentials = jsEncrypt.encrypt(JSON.stringify({ subject, password }))
    return await axios.post(`/v1/auth/login/username`, { crendentials });
}


exports.api = api;
