package com.example.a002045049uasga;

import android.Manifest;
import android.app.ProgressDialog;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.location.Location;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.provider.MediaStore;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.core.app.ActivityCompat;
import androidx.core.content.FileProvider;
import androidx.fragment.app.Fragment;

import com.google.android.gms.location.FusedLocationProviderClient;
import com.google.android.gms.location.LocationServices;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.OnSuccessListener;
import com.example.a002045049uasga.api.ApiService;
import com.example.a002045049uasga.api.RetrofitBuilder;
import com.example.a002045049uasga.model.LaporModel;

import java.io.File;
import java.io.IOException;
import java.text.SimpleDateFormat;
import java.util.Date;

import okhttp3.MediaType;
import okhttp3.MultipartBody;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

/**
 * A simple {@link Fragment} subclass.
 * Use the {@link LaporFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class LaporFragment extends Fragment {
    EditText txt_nama, txt_latitude, txt_longtitude;
    private static final int REQUEST_LOCATION_PERMISSION = 1;
    Button lapor, upload;
    private FusedLocationProviderClient mFusedLocationClient;
    private static final String TAG = "Debug Test";
    ImageView image_upload;
    static final int REQUEST_IMAGE_CAPTURE = 1;
    static final int REQUEST_TAKE_PHOTO = 1;
    String currentPhotoPath;
    Uri photoURI;
    File photoFile;

    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";

    // TODO: Rename and change types of parameters
    private String mParam1;
    private String mParam2;

    public LaporFragment() {
        // Required empty public constructor
    }

    /**
     * Use this factory method to create a new instance of
     * this fragment using the provided parameters.
     *
     * @param param1 Parameter 1.
     * @param param2 Parameter 2.
     * @return A new instance of fragment LaporFragment.
     */
    // TODO: Rename and change types and number of parameters
    public static LaporFragment newInstance(String param1, String param2) {
        LaporFragment fragment = new LaporFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM1, param1);
        args.putString(ARG_PARAM2, param2);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            mParam1 = getArguments().getString(ARG_PARAM1);
            mParam2 = getArguments().getString(ARG_PARAM2);
        }
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        View a = inflater.inflate(R.layout.fragment_lapor, container, false);
        txt_nama = a.findViewById(R.id.txtNamaF);
        txt_latitude = a.findViewById(R.id.txtLatitudeF);
        txt_longtitude = a.findViewById(R.id.txtLongtitudeF);
        lapor = a.findViewById(R.id.button_lapor);
        upload = a.findViewById(R.id.button_upload);
        image_upload = a.findViewById(R.id.image_upload);

        mFusedLocationClient = LocationServices.getFusedLocationProviderClient(getActivity());
        getLokasi();
        lapor.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if(txt_nama.getText().toString().isEmpty() || txt_latitude.getText().toString().isEmpty()
                        ||txt_longtitude.getText().toString().isEmpty() || image_upload.getDrawable() == null){
                    Toast.makeText(getActivity()    , "Inputan & Gambar tidak boleh kosong!!", Toast.LENGTH_SHORT).show();
                }else {
                    uploadFileToServer();
                }
            }
        });

        upload.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                dispatchTakePictureIntent();
            }
        });
        return a;
    }

    private void dispatchTakePictureIntent() {
        Intent takePictureIntent = new Intent(MediaStore.ACTION_IMAGE_CAPTURE);
        // Ensure that there's a camera activity to handle the intent
        if (takePictureIntent.resolveActivity(getActivity().getPackageManager()) != null) {
            // Create the File where the photo should go
            photoFile = null;
            try {
                photoFile = createImageFile();
            } catch (IOException ex) {
                Toast.makeText(getActivity(), "Gagal", Toast.LENGTH_SHORT).show();
            }
            // Continue only if the File was successfully created
            if (photoFile != null) {
                photoURI = FileProvider.getUriForFile(getActivity(),
                        "com.example.a002045049uasga.fileprovider",
                        photoFile);
                takePictureIntent.putExtra(MediaStore.EXTRA_OUTPUT, photoURI);
                startActivityForResult(takePictureIntent, REQUEST_TAKE_PHOTO);
            }
        }
    }

    private File createImageFile() throws IOException {
        // Create an image file name
        String timeStamp = new SimpleDateFormat("yyyyMMdd_HHmmss").format(new Date());
        String imageFileName = "JPEG_" + timeStamp + "_";
        File storageDir = getActivity().getExternalFilesDir(Environment.DIRECTORY_PICTURES);
        File image = File.createTempFile(
                imageFileName,  /* prefix */
                ".jpg",         /* suffix */
                storageDir      /* directory */
        );

        // Save a file: path for use with ACTION_VIEW intents
        currentPhotoPath = image.getAbsolutePath();
        return image;
    }

    private void getLokasi() {
        if (ActivityCompat.checkSelfPermission(getActivity(),
                Manifest.permission.ACCESS_FINE_LOCATION)
                != PackageManager.PERMISSION_GRANTED) {
            ActivityCompat.requestPermissions(getActivity(), new String[]
                            {Manifest.permission.ACCESS_FINE_LOCATION},
                    REQUEST_LOCATION_PERMISSION);
        } else {
            mFusedLocationClient.getLastLocation().addOnSuccessListener(new OnSuccessListener<Location>() {
                @Override
                public void onSuccess(Location location) {
                    if(!(location == null)) {
                        txt_latitude.setText(String.valueOf(location.getLatitude()));
                        txt_longtitude.setText(String.valueOf(location.getLongitude()));
                        txt_latitude.setEnabled(false);
                        txt_longtitude.setEnabled(false);
                    } else {
                        Toast.makeText(getActivity(), "Aktifkan Lokasi", Toast.LENGTH_LONG).show();
                    }
                }
            }).addOnFailureListener(new OnFailureListener() {
                @Override
                public void onFailure(@NonNull Exception e) {
                    Toast.makeText(getActivity(), e.getLocalizedMessage(), Toast.LENGTH_LONG).show();
                }
            });
        }
    }

    @Override
    public void onRequestPermissionsResult(int requestCode,
                                           @NonNull String[] permissions,
                                           @NonNull int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        switch (requestCode) {
            case REQUEST_LOCATION_PERMISSION:

                if (grantResults.length > 0
                        && grantResults[0]
                        == PackageManager.PERMISSION_GRANTED) {
                    getLokasi();
                } else {
                    Toast.makeText(getActivity(),
                            "Izin Ditolak",
                            Toast.LENGTH_SHORT).show();
                }
                break;
        }
    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (requestCode == REQUEST_IMAGE_CAPTURE && resultCode == getActivity().RESULT_OK) {
            image_upload.setImageURI(photoURI);
        }
    }

    private void uploadFileToServer() {
        ProgressDialog loading = ProgressDialog.show(getActivity(),
                "Menambah...", "Mohon tunggu",false, false);

        File file = photoFile;

        final String nama = txt_nama.getText().toString().trim();
        final String latitude = txt_latitude.getText().toString().trim();
        final String longtitude = txt_longtitude.getText().toString().trim();

        RequestBody requestBody = RequestBody.create(MediaType.parse("multipart/form-data"), file);
        MultipartBody.Part fileImage = MultipartBody.Part.createFormData("sendimage",
                "laporan_"+ nama + "_" +System.currentTimeMillis()+".jpg", requestBody);

        ApiService service = RetrofitBuilder.getRetrofit().create(ApiService.class);
        Call<LaporModel> call = service.uploadLaporModel(fileImage, nama,
                Double.valueOf(latitude), Double.valueOf(longtitude));

        call.enqueue(new Callback<LaporModel>() {
            @Override
            public void onResponse(Call<LaporModel> call, Response<LaporModel> response) {
                //berhasil
                Toast.makeText(getActivity(), response.body().getMessage(), Toast.LENGTH_SHORT).show();
                txt_latitude.setEnabled(true);
                txt_longtitude.setEnabled(true);
                startActivity(new Intent(getActivity(), MapsActivity.class));
                loading.dismiss();
            }

            @Override
            public void onFailure(Call<LaporModel> call, Throwable t) {
                //gagal
                Toast.makeText(getActivity(), t.getMessage(), Toast.LENGTH_SHORT).show();
                Log.d(TAG, "onFailure: " + nama + " " + latitude + " " + longtitude);
                loading.dismiss();
            }
        });

    }
}