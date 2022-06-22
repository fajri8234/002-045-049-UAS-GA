package com.example.a002045049uasga.model;

import com.google.gson.annotations.SerializedName;

public class LocationModel {
    @SerializedName("nama")
    private String imageLocationName;
    @SerializedName("latitude")
    private String latitude;
    @SerializedName("longitude")
    private String longitude;
    @SerializedName("verifikasi")
    private String verifikasi;

    public LocationModel(String imageLocationName, String latitude, String longitude, String verifikasi){
        this.imageLocationName = imageLocationName;
        this.latitude = latitude;
        this.longitude = longitude;
        this.verifikasi = verifikasi;
    }

    public LocationModel(){

    }

    public String getImageLocationName() {return imageLocationName;}

    public void setImageLocationName(String imageLocationName) {
        this.imageLocationName = imageLocationName;
    }

    public String getLatitude(){return latitude;}

    public void setLatitude(String latitude){this.latitude = latitude;}

    public String getLongitude() {return longitude;}

    public void setLongitude(String longitude){this.longitude = longitude;}

    public String getVerifikasi() {
        return verifikasi;
    }

    public void setVerifikasi(String verifikasi) {
        this.verifikasi = verifikasi;
    }
}
