--TEST--
binary cleanness (#3)
--SKIPIF--
--FILE--
<?php
require_once dirname(__FILE__) . '/../common/config.php';

$mysql = get_mysql_connection();

init_mysql_testdb($mysql);

$table = 'hstesttbl';
$tablesize = 256;
$sql = sprintf(
    'CREATE TABLE %s (k varchar(30) PRIMARY KEY, v varchar(30)) ' .
    'Engine = innodb default charset = binary',
    mysql_real_escape_string($table));
if (!mysql_query($sql, $mysql))
{
    die(mysql_error());
}

srand(999);

$valmap = array();

echo 'WR', PHP_EOL;
for ($i = 0; $i < $tablesize; $i++)
{
    $k = (string)$i;
    if ($i % 2 == 1)
    {
        $v = $i;
        $sql = sprintf(
            'INSERT INTO ' . $table . ' values (\'%s\', \'%s\')',
            mysql_real_escape_string($k),
            mysql_real_escape_string($v));
    }
    else
    {
        $v = null;
        $sql = sprintf(
            'INSERT INTO ' . $table . ' values (\'%s\', NULL)',
            mysql_real_escape_string($k));
    }

    if (!mysql_query($sql, $mysql))
    {
        break;
    }

    if ($v == null)
    {
        $v = '[null]';
    }

    echo $k, ' ', $v, PHP_EOL;

    $valmap[$k] = $v;
}


$hs = new HandlerSocket(MYSQL_HOST, MYSQL_HANDLERSOCKET_PORT);
if (!($hs->openIndex(1, MYSQL_DBNAME, $table, '', 'k,v')))
{
    die();
}

$retval = $hs->executeSingle(1, '>=', array(''), 10000, 0);

echo 'HS', PHP_EOL;
for ($i = 0; $i < $tablesize; $i++)
{
    $k = $retval[$i][0];
    $v = $retval[$i][1];

    if ($v == null)
    {
        $v = '[null]';
    }

    echo $k, ' ', $v, PHP_EOL;

    if ($valmap[$k] != $v)
    {
        echo 'MISMATCH', PHP_EOL;
    }
}


echo 'MY', PHP_EOL;
$sql = 'SELECT k,v FROM ' . $table . ' ORDER BY k';
$result = mysql_query($sql, $mysql);
if ($result)
{
    while ($row = mysql_fetch_assoc($result))
    {
        if ($row['v'] == null)
        {
            $row['v'] = '[null]';
        }

        echo $row['k'], ' ', $row['v'], PHP_EOL;

        if ($valmap[$row['k']] != $row['v'])
        {
            echo 'MISMATCH', PHP_EOL;
        }
    }
    mysql_free_result($result);
}

mysql_close($mysql);

--EXPECT--
WR
0 [null]
1 1
2 [null]
3 3
4 [null]
5 5
6 [null]
7 7
8 [null]
9 9
10 [null]
11 11
12 [null]
13 13
14 [null]
15 15
16 [null]
17 17
18 [null]
19 19
20 [null]
21 21
22 [null]
23 23
24 [null]
25 25
26 [null]
27 27
28 [null]
29 29
30 [null]
31 31
32 [null]
33 33
34 [null]
35 35
36 [null]
37 37
38 [null]
39 39
40 [null]
41 41
42 [null]
43 43
44 [null]
45 45
46 [null]
47 47
48 [null]
49 49
50 [null]
51 51
52 [null]
53 53
54 [null]
55 55
56 [null]
57 57
58 [null]
59 59
60 [null]
61 61
62 [null]
63 63
64 [null]
65 65
66 [null]
67 67
68 [null]
69 69
70 [null]
71 71
72 [null]
73 73
74 [null]
75 75
76 [null]
77 77
78 [null]
79 79
80 [null]
81 81
82 [null]
83 83
84 [null]
85 85
86 [null]
87 87
88 [null]
89 89
90 [null]
91 91
92 [null]
93 93
94 [null]
95 95
96 [null]
97 97
98 [null]
99 99
100 [null]
101 101
102 [null]
103 103
104 [null]
105 105
106 [null]
107 107
108 [null]
109 109
110 [null]
111 111
112 [null]
113 113
114 [null]
115 115
116 [null]
117 117
118 [null]
119 119
120 [null]
121 121
122 [null]
123 123
124 [null]
125 125
126 [null]
127 127
128 [null]
129 129
130 [null]
131 131
132 [null]
133 133
134 [null]
135 135
136 [null]
137 137
138 [null]
139 139
140 [null]
141 141
142 [null]
143 143
144 [null]
145 145
146 [null]
147 147
148 [null]
149 149
150 [null]
151 151
152 [null]
153 153
154 [null]
155 155
156 [null]
157 157
158 [null]
159 159
160 [null]
161 161
162 [null]
163 163
164 [null]
165 165
166 [null]
167 167
168 [null]
169 169
170 [null]
171 171
172 [null]
173 173
174 [null]
175 175
176 [null]
177 177
178 [null]
179 179
180 [null]
181 181
182 [null]
183 183
184 [null]
185 185
186 [null]
187 187
188 [null]
189 189
190 [null]
191 191
192 [null]
193 193
194 [null]
195 195
196 [null]
197 197
198 [null]
199 199
200 [null]
201 201
202 [null]
203 203
204 [null]
205 205
206 [null]
207 207
208 [null]
209 209
210 [null]
211 211
212 [null]
213 213
214 [null]
215 215
216 [null]
217 217
218 [null]
219 219
220 [null]
221 221
222 [null]
223 223
224 [null]
225 225
226 [null]
227 227
228 [null]
229 229
230 [null]
231 231
232 [null]
233 233
234 [null]
235 235
236 [null]
237 237
238 [null]
239 239
240 [null]
241 241
242 [null]
243 243
244 [null]
245 245
246 [null]
247 247
248 [null]
249 249
250 [null]
251 251
252 [null]
253 253
254 [null]
255 255
HS
0 [null]
1 1
10 [null]
100 [null]
101 101
102 [null]
103 103
104 [null]
105 105
106 [null]
107 107
108 [null]
109 109
11 11
110 [null]
111 111
112 [null]
113 113
114 [null]
115 115
116 [null]
117 117
118 [null]
119 119
12 [null]
120 [null]
121 121
122 [null]
123 123
124 [null]
125 125
126 [null]
127 127
128 [null]
129 129
13 13
130 [null]
131 131
132 [null]
133 133
134 [null]
135 135
136 [null]
137 137
138 [null]
139 139
14 [null]
140 [null]
141 141
142 [null]
143 143
144 [null]
145 145
146 [null]
147 147
148 [null]
149 149
15 15
150 [null]
151 151
152 [null]
153 153
154 [null]
155 155
156 [null]
157 157
158 [null]
159 159
16 [null]
160 [null]
161 161
162 [null]
163 163
164 [null]
165 165
166 [null]
167 167
168 [null]
169 169
17 17
170 [null]
171 171
172 [null]
173 173
174 [null]
175 175
176 [null]
177 177
178 [null]
179 179
18 [null]
180 [null]
181 181
182 [null]
183 183
184 [null]
185 185
186 [null]
187 187
188 [null]
189 189
19 19
190 [null]
191 191
192 [null]
193 193
194 [null]
195 195
196 [null]
197 197
198 [null]
199 199
2 [null]
20 [null]
200 [null]
201 201
202 [null]
203 203
204 [null]
205 205
206 [null]
207 207
208 [null]
209 209
21 21
210 [null]
211 211
212 [null]
213 213
214 [null]
215 215
216 [null]
217 217
218 [null]
219 219
22 [null]
220 [null]
221 221
222 [null]
223 223
224 [null]
225 225
226 [null]
227 227
228 [null]
229 229
23 23
230 [null]
231 231
232 [null]
233 233
234 [null]
235 235
236 [null]
237 237
238 [null]
239 239
24 [null]
240 [null]
241 241
242 [null]
243 243
244 [null]
245 245
246 [null]
247 247
248 [null]
249 249
25 25
250 [null]
251 251
252 [null]
253 253
254 [null]
255 255
26 [null]
27 27
28 [null]
29 29
3 3
30 [null]
31 31
32 [null]
33 33
34 [null]
35 35
36 [null]
37 37
38 [null]
39 39
4 [null]
40 [null]
41 41
42 [null]
43 43
44 [null]
45 45
46 [null]
47 47
48 [null]
49 49
5 5
50 [null]
51 51
52 [null]
53 53
54 [null]
55 55
56 [null]
57 57
58 [null]
59 59
6 [null]
60 [null]
61 61
62 [null]
63 63
64 [null]
65 65
66 [null]
67 67
68 [null]
69 69
7 7
70 [null]
71 71
72 [null]
73 73
74 [null]
75 75
76 [null]
77 77
78 [null]
79 79
8 [null]
80 [null]
81 81
82 [null]
83 83
84 [null]
85 85
86 [null]
87 87
88 [null]
89 89
9 9
90 [null]
91 91
92 [null]
93 93
94 [null]
95 95
96 [null]
97 97
98 [null]
99 99
MY
0 [null]
1 1
10 [null]
100 [null]
101 101
102 [null]
103 103
104 [null]
105 105
106 [null]
107 107
108 [null]
109 109
11 11
110 [null]
111 111
112 [null]
113 113
114 [null]
115 115
116 [null]
117 117
118 [null]
119 119
12 [null]
120 [null]
121 121
122 [null]
123 123
124 [null]
125 125
126 [null]
127 127
128 [null]
129 129
13 13
130 [null]
131 131
132 [null]
133 133
134 [null]
135 135
136 [null]
137 137
138 [null]
139 139
14 [null]
140 [null]
141 141
142 [null]
143 143
144 [null]
145 145
146 [null]
147 147
148 [null]
149 149
15 15
150 [null]
151 151
152 [null]
153 153
154 [null]
155 155
156 [null]
157 157
158 [null]
159 159
16 [null]
160 [null]
161 161
162 [null]
163 163
164 [null]
165 165
166 [null]
167 167
168 [null]
169 169
17 17
170 [null]
171 171
172 [null]
173 173
174 [null]
175 175
176 [null]
177 177
178 [null]
179 179
18 [null]
180 [null]
181 181
182 [null]
183 183
184 [null]
185 185
186 [null]
187 187
188 [null]
189 189
19 19
190 [null]
191 191
192 [null]
193 193
194 [null]
195 195
196 [null]
197 197
198 [null]
199 199
2 [null]
20 [null]
200 [null]
201 201
202 [null]
203 203
204 [null]
205 205
206 [null]
207 207
208 [null]
209 209
21 21
210 [null]
211 211
212 [null]
213 213
214 [null]
215 215
216 [null]
217 217
218 [null]
219 219
22 [null]
220 [null]
221 221
222 [null]
223 223
224 [null]
225 225
226 [null]
227 227
228 [null]
229 229
23 23
230 [null]
231 231
232 [null]
233 233
234 [null]
235 235
236 [null]
237 237
238 [null]
239 239
24 [null]
240 [null]
241 241
242 [null]
243 243
244 [null]
245 245
246 [null]
247 247
248 [null]
249 249
25 25
250 [null]
251 251
252 [null]
253 253
254 [null]
255 255
26 [null]
27 27
28 [null]
29 29
3 3
30 [null]
31 31
32 [null]
33 33
34 [null]
35 35
36 [null]
37 37
38 [null]
39 39
4 [null]
40 [null]
41 41
42 [null]
43 43
44 [null]
45 45
46 [null]
47 47
48 [null]
49 49
5 5
50 [null]
51 51
52 [null]
53 53
54 [null]
55 55
56 [null]
57 57
58 [null]
59 59
6 [null]
60 [null]
61 61
62 [null]
63 63
64 [null]
65 65
66 [null]
67 67
68 [null]
69 69
7 7
70 [null]
71 71
72 [null]
73 73
74 [null]
75 75
76 [null]
77 77
78 [null]
79 79
8 [null]
80 [null]
81 81
82 [null]
83 83
84 [null]
85 85
86 [null]
87 87
88 [null]
89 89
9 9
90 [null]
91 91
92 [null]
93 93
94 [null]
95 95
96 [null]
97 97
98 [null]
99 99
