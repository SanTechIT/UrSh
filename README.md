# UrSh
A lightweight and powerfull PHP url shortener built for Apache!
- If needed, This should work with any web server that has an equivilant of a rewrite module.

Note, Uses geoip data from ipinfo.io. It is suggested that you use your own service. (code expects data in json format)

## Progress
Core Functions

1. Get url DONE
2. Parse Url and get path DONE
3. Check if path exsists in database DONE

  - If exists, record visitor database DONE
  - Check if URL is "Active" and if Owner of URL is "Active" DONE
  - Send user to url stored in database DONE

  - If does not exsist, return error page DONE

Admin Functions

1. Login DONE
2. Normal User WIP
    A. Add DONE / Manage WIP / Delete Urls TBS
    B. See Stats on owned Urls WIP
3. Admin User TBS
    A. Inherit Normal User
    B. Manage and freeze users