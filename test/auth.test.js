const { api } = require('./api');
const { BadRequestException } = require('./exception');

var publicKey;

test('Get public key', async () => {
    const ret = await api.auth.getPublicKey()
    publicKey = ret.data.result.key
})

var username = "testUser" + Math.floor(Math.random() * 100000);



test('Try to register with simple password and username ' + username, async () => {
    var ret;
    try {
        ret = await api.auth.signUpByUsername(username, "123456", publicKey)
    } catch (error) {
        expect.assertions(error instanceof BadRequestException);
        expect.assertions(error.code == 400);
    }
})

test('Try to register with username ' + username, async () => {
    const ret = await api.auth.signUpByUsername(username, "12345678", publicKey)
    expect.assertions(ret.data.result!=null);
    console.log(ret.data.result);
})

test('Try to login with username ' + username, async () => {
    const ret = await api.auth.loginByUsername(username, "12345678", publicKey)
    expect.assertions(ret.data.result!=null);
    console.log(ret.data.result);
})