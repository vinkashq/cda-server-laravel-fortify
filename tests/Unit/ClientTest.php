<?php

use Vinkas\Cda\Server\Client;

test('findValid', function () {
    expect(Client::findValid())->toBeNull();
});
