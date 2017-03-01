# API Endpoints

## Models
<table>
<tr><th>HTTP Method</th> <th>URI</th>        <th>Action</th></tr>
<tr><td>GET</td>         <td>sections</td>       <td>List sections</td></tr>
<tr><td>POST</td>        <td>sections</td>       <td>Create section</td></tr>
<tr><td>PUT</td>         <td>sections/:id</td>   <td>Update section</td></tr>
<tr><td>DELETE</td>      <td>sections/:id</td>   <td>Delete section</td></tr>

<tr><td>GET</td>         <td>articles</td>       <td>List articles</td></tr>
<tr><td>POST</td>        <td>articles</td>       <td>Create article</td></tr>
<tr><td>PUT</td>         <td>articles/:id</td>   <td>Update article</td></tr>
<tr><td>DELETE</td>      <td>articles/:id</td>   <td>Delete article</td></tr>

<tr><td>GET</td>         <td>categories</td>       <td>List categories</td></tr>
<tr><td>POST</td>        <td>categories</td>       <td>Create category</td></tr>
<tr><td>PUT</td>         <td>categories/:id</td>   <td>Update category</td></tr>
<tr><td>DELETE</td>      <td>categories/:id</td>   <td>Delete category</td></tr>

<tr><td>GET</td>         <td>users</td>       <td>List users</td></tr>
<tr><td>POST</td>        <td>users</td>       <td>Create user</td></tr>
<tr><td>PUT</td>         <td>users/:id</td>   <td>Update user</td></tr>
<tr><td>DELETE</td>      <td>users/:id</td>   <td>Delete user</td></tr>
</table>

## Current user & helpers
<table>
<tr><th>HTTP Method</th> <th>URI</th>        <th>Action</th></tr>
<tr><td>GET</td> <td>overview</td>    <td>Get overview (sections > contacts > articles)</td></tr>
<tr><td>GET</td> <td>overview/my</td> <td>Get articles created by current user</td></tr>
<tr><td>GET</td> <td>user</td>        <td>Get current user</td></tr>
<tr><td>GET</td> <td>contacts</td>    <td>Get available contacts in section</td></tr>
<tr><td>GET</td> <td>roles</td>       <td>Get available roles (for admin)</td></tr>
<tr><td>GET</td> <td>token</td>       <td>Get XSRF-TOKEN</td></tr>
</table>
