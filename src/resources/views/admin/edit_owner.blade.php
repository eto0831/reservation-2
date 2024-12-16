@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/edit_owner.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
    {{ session('status') }}
</div>
<div class="container">
    <h2>店舗代表者情報編集</h2>
    <div class="owner-edit__container">

        <!-- 担当店舗のリスト表示 -->
        <div class="form__group">
            <h3 class="form__group-title">{{ $owner->name }}さんの担当店舗一覧</ｈ>
            <div class="form__group-content">
                @if($owner->shops->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>店舗ID</th>
                            <th>店舗名</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($owner->shops as $shop)
                        <tr>
                            <td>{{ $shop->id }}</td>
                            <td>{{ $shop->shop_name }}</td>
                            <td>
                                <form action="{{ route('admin.owner.detachShop') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="owner_id" value="{{ $owner->id }}">
                                    <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                                    <button type="submit" onclick="return confirm('この店舗の担当を解除しますか？')">担当解除</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p>現在、担当している店舗はありません。</p>
                @endif
            </div>
        </div>

        <!-- 新しい店舗の追加フォーム -->
        <div class="form__group">
            <div class="form__group-title">新しい店舗を追加</div>
            <div class="form__group-content">
                <form action="{{ route('admin.owner.attachShop') }}" method="post">
                    @csrf
                    <input type="hidden" name="owner_id" value="{{ $owner->id }}">
                    <div class="form__group-content">
                        <select name="shop_id">
                            <option value="">店舗を選択してください</option>
                            @foreach($shops as $shop)
                            @if(!$owner->shops->contains($shop))
                            <option value="{{ $shop->id }}">{{ $shop->id }} : {{ $shop->shop_name }} : {{
                                $shop->area->area_name }} : {{ $shop->genre->genre_name }}</option>
                            @endif
                            @endforeach
                        </select>
                        <button type="submit" onclick="return confirm('担当店舗を追加しますか？')">追加</button>
                    </div>
                </form>
            </div>
        </div>
        

        <!-- ユーザー情報の更新フォーム -->
        <form action="{{ route('admin.owner.update') }}" method="post">
            @csrf
            @method('PATCH')
            <input type="hidden" name="owner_id" value="{{ $owner->id }}">
            <div class="form__group">
                <div class="form__group-title">氏名</div>
                <div class="form__group-content">
                    <input type="text" name="name" value="{{ old('name', $owner->name) }}" />
                    @error('name') <div class="form__error">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-title">メールアドレス</div>
                <div class="form__group-content">
                    <input type="email" name="email" value="{{ old('email', $owner->email) }}" />
                    @error('email') <div class="form__error">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-title">新しいパスワード (変更時のみ入力)</div>
                <div class="form__group-content">
                    <input type="password" name="password" />
                    @error('password') <div class="form__error">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="form__button-group">
                <a href="{{ url()->previous() }}" class="form__button-back">戻る</a>
                <button class="form__button-submit" type="submit" onclick="return confirm('この内容で確定しますか？')">変更を確定</button>
            </div>
        </form>
    </div>
</div>
@endsection
