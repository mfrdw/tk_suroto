<?php

namespace App\Controllers;

use App\Models\Models_Produk_Kategori;
use App\Models\Models_Konfigurasi;


class Home extends BaseController
{

    protected $Produk_Kategori;
    protected $Konfigurasi;



    public function __construct()
    {

        $this->Produk_Kategori = new Models_Produk_Kategori();

        $this->Konfigurasi = new Models_Konfigurasi();

    }



    /*
    |--------------------------------------------------------------------------
    | BERANDA
    |--------------------------------------------------------------------------
    */

    public function index()
    {

        $data = [

            'title' => 'Toko Suroto - Beranda',

            'konfigurasi' =>
                $this->Konfigurasi->getKonfigurasi(),


            'produk' =>
                $this->Produk_Kategori
                ->getProdukTerbaru(8),


            'produk_random' =>
                $this->Produk_Kategori
                ->getProdukRandom(4),

        ];


        return view('home/index',$data);

    }





    /*
    |--------------------------------------------------------------------------
    | DAFTAR PRODUK
    |--------------------------------------------------------------------------
    */

    public function produk()
    {

        $kategori_id = $this->request->getGet('kategori');

        $keyword = $this->request->getGet('cari');



        $data = [

            'title' => 'Toko Suroto - Produk',


            'konfigurasi' =>
                $this->Konfigurasi->getKonfigurasi(),



            'produk' =>
                $this->Produk_Kategori
                ->getProdukFilter(
                    $kategori_id,
                    $keyword
                ),



            'kategori_id' =>
                $kategori_id,



            'keyword' =>
                $keyword,

        ];



        return view('home/produk',$data);

    }





    /*
    |--------------------------------------------------------------------------
    | DETAIL PRODUK
    |--------------------------------------------------------------------------
    */

    public function detail($slug)
    {


        $produk = 
            $this->Produk_Kategori
            ->getProdukBySlug($slug);



        if(!$produk)
        {

            throw new \CodeIgniter\Exceptions\PageNotFoundException(
                'Produk tidak ditemukan'
            );

        }



        $data = [


            'title' =>
                'Toko Suroto - Detail Produk',



            'konfigurasi' =>
                $this->Konfigurasi->getKonfigurasi(),



            'produk' =>
                $produk,



            'produk_related' =>
                $this->Produk_Kategori
                ->getProdukRelated(
                    4,
                    $produk['id_produk']
                ),


        ];



        return view('home/detail',$data);


    }





    /*
    |--------------------------------------------------------------------------
    | TENTANG
    |--------------------------------------------------------------------------
    */

    public function tentang()
    {

        $data = [

            'title' =>
                'Toko Suroto - Tentang Kami',


            'konfigurasi' =>
                $this->Konfigurasi->getKonfigurasi(),

        ];


        return view('home/tentang',$data);


    }





    /*
    |--------------------------------------------------------------------------
    | KONTAK
    |--------------------------------------------------------------------------
    */

    public function kontak()
    {

        $data = [

            'title' =>
                'Toko Suroto - Kontak',


            'konfigurasi' =>
                $this->Konfigurasi->getKonfigurasi(),

        ];


        return view('home/kontak',$data);


    }


}