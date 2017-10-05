## Common problems

### 1 In tests I always get jwt invalid token exception

In ``phpunit.xml`` you need to set up the env variable `JWT_BLACKLIST_ENABLED` to `false`. This problem occurs when you try to make two consecutive calls to a protected route. The first one goes fine, but the second one needs the refreshed token returned in the response from the first request. To avoid unnecessary code in tests to transfer token from a response to a request, we just decided to turn off the token black list feature (which make the toke invalid after its use) for the tests.