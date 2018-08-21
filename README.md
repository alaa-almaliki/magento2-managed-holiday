# Magento 2 Managed Holiday

Magento 2 extension to manage public and/or custom holidays from the admin
configured in **Admin => Sales => Managed Holiday**. It also supports multiple stores.

# Install

`composer require alaa/magento2-managed-holiday`

# Documentation

## CRUD
Use `\Alaa\ManagedHoliday\Model\HolidayRepository` for CRUD operationss

## Helper Methods Api:

check if current/given date is holiday:

```
\Alaa\ManagedHoliday\Helper\Holiday::isHoliday(string $input = null, $storeId = null): bool
```

Get next holiday or by a given date:
```
\Alaa\ManagedHoliday\Helper\Holiday::getHoliday(string $input = null, $storeId = null)
```

Get Holidays between two given dates:
```
\Alaa\ManagedHoliday\Helper\Holiday::between(string $from, string $to, $storeId = null): array
```


## JS Api

Get Holiday By ID:
```
define(['holiday'], function (holiday) {
    holiday.getHoliday(function (response) {
        // do something
    }, 1)
});
```

Check if is current/given date is holiday:
```
define(['holiday'], function (holiday) {
    holiday.isHoliday(function (response) {
        // do something
    }, {input: "01/01/2018"})
});
```

Get next holidays:
```
define(['holiday'], function (holiday) {
    holiday.nextHolidays(function (response) {
        // do something
    }, {})// could be store_id passed in
});
```

Get holidays between two given dates:
```
define(['holiday'], function (holiday) {
    holiday.between(function (response) {
        // do something
    }, {"from": "12/01/2018", "to": "12/29/2018"})
});
```

# Contribution
Please feel free to raise issues and contribute

# License
**MIT**