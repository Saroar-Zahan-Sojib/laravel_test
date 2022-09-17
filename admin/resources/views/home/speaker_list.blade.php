@forelse($spk as $key => $dt)
<tr>
    <td>{{$key+1}}</td>
    <td class="name">{{$dt->speaker_name}}</td>
    <td class="type_name"><img src="{{ asset('files/'.$dt->speaker_image) }}" alt="Image" class="d-flex rounded mr-3"></td>
                            
</tr>

@empty
<code>No Speaker Found</code>
@endforelse