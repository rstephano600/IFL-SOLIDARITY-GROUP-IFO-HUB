@extends('layouts.configside') 
@section('title', 'User Permissions Access Informations')
@section('page-title', 'User Permissions Access')

@section('content')
<!-- Page Header styling alignment -->
<div class="arbif-page-header" style="margin-bottom: 25px;">
    <h3>
        <div class="page-icon" style="background: linear-gradient(135deg, #4e73df, #224abe); color: white; display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 8px; margin-right: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <i class="fas fa-users-cog"></i>
        </div>
        User Permissions Access Informations
    </h3>
</div>

<div class="arbif-card" style="background: #ffffff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #e3e6f0; overflow: hidden;">
    <div class="arbif-card-body" style="padding: 20px;">
        <div class="arbif-table-wrap" style="overflow-x: auto;">
            <table class="arbif-table" id="countryTable" style="width: 100%; border-collapse: collapse; min-width: 600px;">
                <thead>
                    <tr style="background-color: #f8f9fc; border-bottom: 2px solid #e3e6f0;">
                        <th style="padding: 15px 12px; text-align: left; color: #4e73df; font-weight: 700; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">User Info</th>
                        <th style="padding: 15px 12px; text-align: left; color: #4e73df; font-weight: 700; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Email Address</th>
                        <th style="padding: 15px 12px; text-align: center; color: #4e73df; font-weight: 700; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; width: 220px;">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($users as $user)
                    <tr style="border-bottom: 1px solid #eaecf4; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#f8f9fc'" onmouseout="this.style.backgroundColor='transparent'">
                        <!-- User Name with custom inline Avatar badge setup -->
                        <td style="padding: 16px 12px; vertical-align: middle;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: #e8f0fe; color: #1a73e8; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px; border: 1px solid #d2e3fc;">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <span style="font-weight: 600; color: #2e384d; font-size: 14px;">{{ $user->name }}</span>
                            </div>
                        </td>
                        
                        <!-- Email Block -->
                        <td style="padding: 16px 12px; vertical-align: middle; color: #6b778c; font-size: 14px;">
                            <i class="far fa-envelope" style="margin-right: 6px; color: #a5b0c1;"></i>{{ $user->email }}
                        </td>
                        
                        <!-- Styled Configuration Button Block -->
                        <td style="padding: 16px 12px; vertical-align: middle; text-align: center;">
                            <a href="{{ route('assignRole', $user->id) }}" 
                               style="display: inline-flex; align-items: center; gap: 8px; background: #4e73df; color: #ffffff; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none; box-shadow: 0 2px 4px rgba(78, 115, 223, 0.2); transition: all 0.2s ease-in-out;"
                               onmouseover="this.style.background='#2e59d9'; this.style.boxShadow='0 4px 8px rgba(78, 115, 223, 0.3)';" 
                               onmouseout="this.style.background='#4e73df'; this.style.boxShadow='0 2px 4px rgba(78, 115, 223, 0.2)';">
                                <i class="fas fa-shield-alt"></i> Assign Permissions
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
