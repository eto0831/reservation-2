@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/edit_owner.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
    {{ session('status') }}
</div>
<div class="container">
    <h1>オーナー編集</h1>
    <div class="owner-edit __container">
        <!-- エラーメッセージの表示 -->
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- ユーザー情報の更新フォーム -->
        <form action="{{ route('admin.owner.update') }}" method="post">
            @csrf
            @method('PATCH')
            <input type="hidden" name="owner_id" value="{{ $owner->id }}">
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">氏名</span>
                </div>
                <div class="form__group-content">
                    <input type="text" name="name" value="{{ old('name', $owner->name) }}" />
                    @error('name') <div class="form__error">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">メールアドレス</span>
                </div>
                <div class="form__group-content">
                    <input type="email" name="email" value="{{ old('email', $owner->email) }}" />
                    @error('email') <div class="form__error">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">新しいパスワード</span>
                </div>
                <div class="form__group-content">
                    <input type="password" name="password" />
                    @error('password') <div class="form__error">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">確認用パスワード</span>
                </div>
                <div class="form__group-content">
                    <input type="password" name="password_confirmation" />
                </div>
            </div>
            <div class="form__button">
                <button class="form__button-submit" type="submit">ユーザー情報を更新</button>
            </div>
        </form>

        <!-- 担当店舗のリスト表示 -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">担当店舗一覧</span>
            </div>
            <div class="form__group-content">
                @if($owner->shops->count() > 0)
                <table border="1">
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
                                <form action="{{ route('admin.owner.detachShop') }}" method="post"
                                    style="display:inline;">
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
            <div class="form__group-title">
                <span class="form__label--item">新しい店舗を追加</span>
            </div>
            <div class="form__group-content">
                <form action="{{ route('admin.owner.attachShop') }}" method="post">
                    @csrf
                    <input type="hidden" name="owner_id" value="{{ $owner->id }}">
                    <select name="shop_id">
                        <option value="">店舗を選択してください</option>
                        @foreach($shops as $shop)
                        @if(!$owner->shops->contains($shop))
                        <option value="{{ $shop->id }}">{{ $shop->id }} : {{ $shop->shop_name }} : {{
                            $shop->area->area_name }} : {{ $shop->genre->genre_name }}</option>
                        @endif
                        @endforeach
                    </select>
                    <button type="submit">追加</button>
                </form>
                <div class="form__error">
                    @error('shop_id')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>
@endsection